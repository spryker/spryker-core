<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication;

use SprykerFeature\Zed\Discount\Communication\Form\CartRuleForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use Generated\Shared\Transfer\DataTablesTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Discount\Communication\Form\CollectorPluginForm;
use SprykerFeature\Zed\Discount\Communication\Form\DecisionRuleForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesForm;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountsTable;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\DiscountDependencyProvider;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Symfony\Component\Form\FormTypeInterface;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolTable;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * @method DiscountQueryContainer getQueryContainer()
 * @method DiscountConfig getConfig()
 */
class DiscountDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param bool $allowMultiple
     *
     * @return FormTypeInterface
     */
    public function createVoucherForm($allowMultiple=false)
    {
        $voucherForm = new VoucherForm(
            $this->getQueryContainer(),
            $this->getConfig(),
            $allowMultiple
        );

        return $this->createForm($voucherForm);
    }

    /**
     * @deprecated Pass the facade via DI.
     *
     * @return DiscountFacade
     */
    public function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return VoucherPoolCategoryTable
     */
    public function createPoolCategoriesTable()
    {
        $poolCategoriesQuery = $this->getQueryContainer()->queryDiscountVoucherPoolCategory();

        return new VoucherPoolCategoryTable($poolCategoriesQuery);
    }

    /**
     * @return DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable($discountQuery);
    }

    /**
     * @param int $idPool
     * @param int $batchValue
     *
     * @return DiscountVoucherCodesTable
     */
    public function createDiscountVoucherCodesTable(DataTablesTransfer $dataTablesTransfer, $idPool, $batchValue)
    {
        return new DiscountVoucherCodesTable(
            $dataTablesTransfer,
            $this->getQueryContainer(),
            $idPool,
            $batchValue
        );
    }

    /**
     * @return VoucherPoolTable
     */
    public function createVoucherPoolTable()
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return new VoucherPoolTable($poolQuery, $this->getConfig());
    }

    /**
     * @param DiscountFacade $discountFacade
     *
     * @return FormTypeInterface
     */
    public function createCartRuleForm(DiscountFacade $discountFacade)
    {
        $cartRuleForm = new CartRuleForm(
            $this->getConfig(),
            $discountFacade
        );

        return $this->createForm($cartRuleForm);
    }

    /**
     * @return CollectorPluginForm
     */
    public function createCollectorPluginForm()
    {
        $collectorPluginForm = new CollectorPluginForm(
            $this->getConfig()->getAvailableCollectorPlugins()
        );

        return $this->createForm($collectorPluginForm);
    }

    /**
     * @return VoucherCodesForm
     */
    public function createVoucherCodesForm()
    {
        $voucherCodesForm = new VoucherCodesForm(
            $this->getConfig(),
            $this->createCamelCaseToUnderscoreFilter(),
            $this->getQueryContainer()
        );

        return $this->createForm($voucherCodesForm);
    }

    /**
     * @return CamelCaseToUnderscore
     */
    public function createCamelCaseToUnderscoreFilter()
    {
        return new CamelCaseToUnderscore();
    }

    /**
     * @return DecisionRuleForm
     */
    public function createDecisionRuleFormType()
    {
        return new DecisionRuleForm($this->getConfig()->getAvailableDecisionRulePlugins());
    }

    /**
     * @return DecisionRuleForm
     */
    public function createDecisionRuleForm()
    {
        $decisionRulesForm = new DecisionRuleForm(
            $this->getConfig()->getAvailableDecisionRulePlugins()
        );

        return $this->createForm($decisionRulesForm);
    }

    /**
     * @param int $idPool
     *
     * @return VoucherPoolTransfer
     */
    public function getVoucherPoolById($idPool)
    {
        $pool = $this->getQueryContainer()
            ->queryDiscountVoucherPool()
            ->findOneByIdDiscountVoucherPool($idPool);

        return (new VoucherPoolTransfer())->fromArray($pool->toArray(), true);
    }

    /**
     * @param $idDiscount
     *
     * @return DiscountTransfer
     */
    public function getDiscountById($idDiscount)
    {
        $discount = $this->getQueryContainer()
            ->queryDiscount()
            ->filterByIdDiscount($idDiscount)
            ->findOne();

        return (new DiscountTransfer())->fromArray($discount->toArray(), true);
    }

    /**
     * @param $idDiscountVoucherPool
     *
     * @return DiscountTransfer
     */
    public function getDiscountByIdDiscountVoucherPool($idDiscountVoucherPool)
    {
        $discount = $this->getQueryContainer()
            ->queryDiscount()
            ->filterByFkDiscountVoucherPool($idDiscountVoucherPool)
            ->findOne();

        return (new DiscountTransfer())->fromArray($discount->toArray(), true);
    }

    /**
     * @param int $idPool
     *
     * @return int
     */
    public function getGeneratedVouchersCountByIdPool($idPool)
    {
        return $this->getQueryForGeneratedVouchersByIdPool($idPool)
            ->count();
    }

    /**
     * @param int $idPool
     *
     * @return SpyDiscountVoucherQuery
     */
    public function getQueryForGeneratedVouchersByIdPool($idPool)
    {
        return $this->getQueryContainer()
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($idPool);
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

}
