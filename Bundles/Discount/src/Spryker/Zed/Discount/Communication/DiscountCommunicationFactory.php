<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication;

use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Generated\Shared\Transfer\DataTablesTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Communication\Form\CollectorPluginForm;
use Spryker\Zed\Discount\Communication\Form\DecisionRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Symfony\Component\Form\Form;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolTable;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * @method DiscountQueryContainer getQueryContainer()
 * @method DiscountConfig getConfig()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param bool $allowMultiple
     *
     * @return Form
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
     * @param DataTablesTransfer $dataTablesTransfer
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
     * @return Form
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
     * @return Form
     */
    public function createCollectorPluginForm()
    {
        $collectorPluginForm = new CollectorPluginForm(
            $this->getConfig()->getAvailableCollectorPlugins()
        );

        return $this->createForm($collectorPluginForm);
    }

    /**
     * @return Form
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
    public function createDecisionRuleForm()
    {
        $decisionRulesForm = new DecisionRuleForm(
            $this->getConfig()
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
