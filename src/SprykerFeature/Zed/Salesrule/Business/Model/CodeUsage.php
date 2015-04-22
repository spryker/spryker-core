<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

class CodeUsage
{

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * Method is called after all dependencies are injected.
     * Use this as a replacement of __contruct() if you want to use injected objects.
     */
    public function initAfterDependencyInjection()
    {
        $this->finder = $this->factory->createModelFinder();
    }

    /**
     * @param string $code
     * @param int|null $customerId
     * @return bool
     */
    public function canUseCouponCode($code, $customerId = null)
    {
        $codeEntity = $this->finder->getCouponCodeByCode($code);

        if (!$codeEntity) {
            return false;
        }

        // the code and the corresponding codepool must be active
        if (($codeEntity->getIsActive() != true) || ($codeEntity->getCodepool()->getIsActive() != true)) {
            return false;
        }

        // has this code already been used by someone before?
        if (!$codeEntity->getCodepool()->getIsReusable() && $this->getCodeUsageCount($code) > 0) {
            return false;
        }

        // a customer can only use the coupon code once
        if ($codeEntity->getCodepool()->getIsOncePerCustomer() && $this->getCodeUsageCountByCustomerId($code, $customerId) > 0) {
            return false;
        }

        if (!$this->factory->createModelCode()->isCodeInActiveSalesruleCondition($code)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idSalesOrder
     * @param string $code
     * @param \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer|null $customer
     * @return bool
     */
    public function createAndSaveCodeUsage($idSalesOrder, $code, \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer = null)
    {
        $codeUsage = new \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsage();
        $codeEntity = $this->finder->getCouponCodeByCode($code);

        if (null === $codeEntity) {
            return false;
        }

        $codeUsage->setCode($codeEntity);
        $codeUsage->setCustomer($customer);
        $codeUsage->setFkSalesOrder($idSalesOrder);
        $codeUsage->save();
        return true;
    }

    /**
     * @param int $orderId
     * @return array
     */
    public function purgeSalesruleCodeUsage($orderId)
    {
        $codeUsages = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsageQuery::create()
            ->findByFkSalesOrder($orderId);

        \Propel\Runtime\Propel::getConnection()->beginTransaction();
        $codes = [];

        foreach ($codeUsages as $codeUsage) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsage $codeUsage */
            $codeUsage->delete();

            $codes[] = $codeUsage->getCode()->getCode();
        }
        \Propel\Runtime\Propel::getConnection()->commit();

        return $codes;
    }

    /**
     * @param string $code
     * @return int
     */
    public function getCodeUsageCount($code)
    {
        $codeEntity = $this->finder->getCouponCodeByCode($code);

        return ($codeEntity)? $codeEntity->getCodeUsages()->count() : 0;
    }

    /**
     * @param int $orderId
     * @return int
     */
    public function getCodeUsageCountForOrder($orderId)
    {
        $codeUsages = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsageQuery::create()
            ->filterByFkSalesOrder($orderId)
            ->find();

        return $codeUsages->count();
    }

    /**
     * @param string $code
     * @param int $customerId
     * @return int
     */
    public function getCodeUsageCountByCustomerId($code, $customerId)
    {
        $codeEntity = $this->finder->getCouponCodeByCode($code);
        $criteria = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsageQuery::create()
            ->filterByFkCustomer($customerId);

        return ($codeEntity)? $codeEntity->getCodeUsages($criteria)->count() : 0;
    }

    /**
     * @param int $idSalesOrder
     * @param array $codes
     * @param \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer
     */
    public function addCodeUsage($idSalesOrder, array $codes, \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer = null)
    {
        foreach ($codes as $code) {
            $this->doAddCodeUsage($idSalesOrder, $code, $customer);
        }
    }

    /**
     * @param int $idSalesOrder
     * @param string $code
     * @param \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer
     */
    protected function doAddCodeUsage($idSalesOrder, $code, \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer = null)
    {
        $codeUsage = new \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsage();
        $codeEntity = $this->finder->getCouponCodeByCode($code);

        if (null === $codeEntity) {
            return;
        }

        $codeUsage->setCode($codeEntity);
        $codeUsage->setCustomer($customer);
        $codeUsage->setFkSalesOrder($idSalesOrder);
        $codeUsage->save();
    }
}
