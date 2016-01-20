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
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Symfony\Component\Form\FormTypeInterface;
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

        return new VoucherPoolTable($poolQuery, $this->getCalculatorPlugins());
    }

    /**
     * @param DiscountFacade $discountFacade
     *
     * @return FormTypeInterface
     */
    public function createCartRuleForm(DiscountFacade $discountFacade)
    {
        $cartRuleForm = new CartRuleForm(
                $discountFacade,
                $this->getCalculatorPlugins(),
                $this->getCollectorPlugins(),
                $this->getDecisionRulePlugins()
            );

        return $this->createForm($cartRuleForm);
    }

    /**
     * @return CollectorPluginForm
     */
    public function createCollectorPluginForm()
    {
        $collectorPluginForm = new CollectorPluginForm(
            $this->getCalculatorPlugins(),
            $this->getCollectorPlugins(),
            $this->getDecisionRulePlugins()
        );

        return $this->createForm($collectorPluginForm);
    }

    /**
     * @return VoucherCodesForm
     */
    public function createVoucherCodesForm()
    {
        $voucherCodesForm = new VoucherCodesForm(
            $this->createCamelCaseToUnderscoreFilter(),
            $this->getQueryContainer(),
            $this->getCalculatorPlugins(),
            $this->getCollectorPlugins(),
            $this->getDecisionRulePlugins()
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
        return new DecisionRuleForm(
            $this->getCalculatorPlugins(),
            $this->getCollectorPlugins(),
            $this->getDecisionRulePlugins()
        );
    }

    /**
     * @return DecisionRuleForm
     */
    public function createDecisionRuleForm()
    {
        $decisionRulesForm = new DecisionRuleForm(
            $this->getCalculatorPlugins(),
            $this->getCollectorPlugins(),
            $this->getDecisionRulePlugins()
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

    /**
     * @return DiscountDecisionRulePluginInterface[]
     */
    public function getDecisionRulePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
    }

    /**
     * @return DiscountCalculatorPluginInterface[]
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return DiscountCollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
    }

}
