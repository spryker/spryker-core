<?php
namespace SprykerFeature\Zed\Salesrule\Business\Model;

class Finder
{
    /**
     * @var array
     */
    protected $validScopes = [
        \SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap::COL_SCOPE_LOCAL,
        \SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap::COL_SCOPE_GLOBAL
    ];

    /**
     * @param string $scope
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule[]
     * @throws \LogicException
     */
    public function getActiveSalesrules($scope = null)
    {
        $query = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create();
        if (null !== $scope) {
            if (!in_array($scope, $this->validScopes)) {
                throw new \LogicException('Unknown salesrule scope. Only local and global is allowed.');
            }
            $query->filterByScope($scope);
        }

        return $query->filterByIsActive(1)->find();
    }

    /**
     * @param string $code
     * @return bool
     */
    public function canRefundCouponCode($code)
    {
        $codeEntity = $this->getCouponCodeByCode($code);

        return ($codeEntity)? $codeEntity->getCodepool()->getIsRefundable() : false;
    }

    /**
     * @param string $code
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode
     */
    public function getCouponCodeByCode($code)
    {
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()
            ->findOneByCode($code);
    }
}
