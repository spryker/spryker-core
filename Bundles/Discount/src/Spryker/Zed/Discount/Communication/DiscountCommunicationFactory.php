<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication;

use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Generated\Shared\Transfer\DataTablesTransfer;
use Spryker\Zed\Discount\Communication\Form\CollectorPluginForm;
use Spryker\Zed\Discount\Communication\Form\DecisionRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use Spryker\Zed\Discount\Communication\Table\VoucherPoolTable;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param bool $allowMultiple
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherForm($allowMultiple = false)
    {
        $voucherForm = new VoucherForm(
            $this->getQueryContainer(),
            $this->getConfig(),
            $allowMultiple
        );

        return $this->createForm($voucherForm);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\VoucherPoolCategoryTable
     */
    public function createPoolCategoriesTable()
    {
        $poolCategoriesQuery = $this->getQueryContainer()->queryDiscountVoucherPoolCategory();

        return new VoucherPoolCategoryTable($poolCategoriesQuery);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable($discountQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     * @param int $idPool
     * @param int $batchValue
     *
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable
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
     * @return \Spryker\Zed\Discount\Communication\Table\VoucherPoolTable
     */
    public function createVoucherPoolTable()
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return new VoucherPoolTable($poolQuery, $this->getConfig());
    }

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacade $discountFacade
     *
     * @return \Symfony\Component\Form\FormInterface
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCollectorPluginForm()
    {
        $collectorPluginForm = new CollectorPluginForm(
            $this->getConfig()
        );

        return $this->createForm($collectorPluginForm);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
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
     * @return \Zend\Filter\Word\CamelCaseToUnderscore
     */
    public function createCamelCaseToUnderscoreFilter()
    {
        return new CamelCaseToUnderscore();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DecisionRuleForm
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
     * @return \Generated\Shared\Transfer\VoucherPoolTransfer
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
     * @return \Generated\Shared\Transfer\DiscountTransfer
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
     * @return \Generated\Shared\Transfer\DiscountTransfer
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function getQueryForGeneratedVouchersByIdPool($idPool)
    {
        return $this->getQueryContainer()
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($idPool);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

}
