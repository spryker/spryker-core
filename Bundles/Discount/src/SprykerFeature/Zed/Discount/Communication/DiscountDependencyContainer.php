<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication;

use Generated\Shared\Transfer\DataTablesTransfer;
use SprykerFeature\Zed\Discount\Communication\Form\CollectorPluginType;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesType;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountsTable;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCommunication;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\Form\CartRuleType;
use SprykerFeature\Zed\Discount\Communication\Form\DecisionRuleType;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\DiscountDependencyProvider;
use SprykerFeature\Zed\Discount\Communication\Table\DiscountVoucherTable;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolTable;
use SprykerFeature\Zed\Discount\Communication\Form\PoolCategoryForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * @method DiscountCommunication getFactory()
 * @method DiscountConfig getConfig()
 */
class DiscountDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param bool $allowMultiple
     *
     * @return VoucherForm
     */
    public function createVoucherForm($allowMultiple=false)
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return $this->getFactory()->createFormVoucherForm($poolQuery, $this->getConfig(), $allowMultiple);
    }

    /**
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

        return $this->getFactory()->createTableVoucherPoolCategoryTable($poolCategoriesQuery);
    }

    /**
     * @return DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return $this->getFactory()->createTableDiscountsTable($discountQuery);
    }

    /**
     * @param int $idPool
     * @param int $batchValue
     *
     * @return DiscountVoucherCodesTable
     */
    public function createDiscountVoucherCodesTable(DataTablesTransfer $dataTablesTransfer, $idPool, $batchValue)
    {
        return $this->getFactory()->createTableDiscountVoucherCodesTable(
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

        return $this->getFactory()->createTableVoucherPoolTable($poolQuery, $this->getConfig());
    }

    /**
     * @return DiscountVoucherTable
     */
    public function createDiscountVoucherTable()
    {
        $discountVoucherQuery = $this->getQueryContainer()->queryDiscountVoucher();

        return $this->getFactory()->createTableDiscountVoucherTable($discountVoucherQuery);
    }

    /**
     * @param id $idPoolCategory
     *
     * @return PoolCategoryForm
     */
    public function createPoolCategoryForm($idPoolCategory)
    {
        $poolCategoryQuery = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
        ;

        return $this->getFactory()->createFormPoolCategoryForm($poolCategoryQuery, $idPoolCategory);
    }

    /**
     * @param FormTypeInterface $form
     * @param array $defaultData
     *
     * @return CartRuleType
     */
    public function createCartRuleForm(FormTypeInterface $form, array $defaultData = null)
    {
        $defaultDataArray = $this->getCartRuleDefaultData();
        if (true === is_array($defaultData)) {
            $defaultDataArray = array_merge($this->getCartRuleDefaultData(), $defaultData);
        }

        $this->getDiscountFacade();

        $formFactory = $this->getFormFactory();

        return $formFactory->create($form, $defaultDataArray, [
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * @return array
     */
    protected function getCartRuleDefaultData()
    {
        return [
            'decision_rules' => [
                'rule_1' => [
                    'value' => '',
                    'rules' => '',
                ],
            ],
            'collector_plugins' => [
                'plugin_1' => [
                    'collector_plugin' => '',
                    'value' => '',
                ],
            ],
            'group' => [],
        ];
    }

    /**
     * @return CartRuleType
     */
    public function createCartRuleFormType()
    {
        return new CartRuleType(
            $this->getConfig()->getAvailableCalculatorPlugins(),
            $this->getConfig()->getAvailableCollectorPlugins(),
            $this->getConfig()->getAvailableDecisionRulePlugins()
        );
    }

    /**
     * @return CollectorPluginType
     */
    public function createCollectorPluginFormType()
    {
        return new CollectorPluginType(
            $this->getConfig()->getAvailableCollectorPlugins()
        );
    }

    /**
     * @return VoucherCodesType
     */
    public function createVoucherCodesFormType()
    {
        return new VoucherCodesType(
            $this->getConfig(),
            $this->getDiscountFacade()->getVoucherPoolCategories(),
            $this->createCamelCaseToUnderscoreFilter()
        );
    }

    /**
     * @return CamelCaseToUnderscore
     */
    public function createCamelCaseToUnderscoreFilter()
    {
        return new CamelCaseToUnderscore();
    }

    /**
     * @return DecisionRuleType
     */
    public function createDecisionRuleFormType()
    {
        return new DecisionRuleType($this->getConfig()->getAvailableDecisionRulePlugins());
    }

    /**
     * @param $idPool
     *
     * @return Form\PoolForm
     */
    public function createPoolForm($idPool=0)
    {
        $poolQuery = $this->getQueryContainer()
            ->queryDiscountVoucherPool()
        ;

        $discountQuery = $this->getQueryContainer()
            ->queryDiscount()
        ;

        $store = $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);

        return $this->getFactory()->createFormPoolForm($poolQuery, $discountQuery, $store, $idPool);
    }

    /**
     * @param Request $request
     *
     * @return DecisionRuleForm
     */
    public function getDecisionRuleForm(Request $request)
    {
        return $this->getFactory()->createFormDecisionRuleForm(
            $request,
            $this->getQueryContainer(),
            $this->getDiscountFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return DiscountForm
     */
    public function getDiscountForm(Request $request)
    {
        return $this->getFactory()->createFormDiscountForm(
            $request,
            $this->getQueryContainer(),
            $this->getDiscountFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherForm
     */
    public function getVoucherForm(Request $request)
    {
        return $this->getFactory()->createFormVoucherForm(
            $request,
            $this->getQueryContainer(),
            $this->getDiscountFacade(),
            $this->getFactory()
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherPoolCategoryForm
     */
    public function getVoucherPoolCategoryForm(Request $request)
    {
        return $this->getFactory()->createFormVoucherPoolCategoryForm(
            $request,
            $this->getQueryContainer(),
            $this->getDiscountFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherPoolForm
     */
    public function getVoucherPoolForm(Request $request)
    {
        return $this->getFactory()->createFormVoucherPoolForm(
            $request,
            $this->getQueryContainer(),
            $this->getDiscountFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return DiscountGrid
     */
    public function getDiscountGrid(Request $request)
    {
        return $this->getFactory()->createGridDiscountGrid(
            $this->getQueryContainer()->queryDiscount(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return DecisionRuleGrid
     */
    public function getDecisionRuleGrid(Request $request)
    {
        return $this->getFactory()->createGridDecisionRuleGrid(
            $this->getQueryContainer()->queryDiscountDecisionRule(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherGrid
     */
    public function getVoucherGrid(Request $request)
    {
        return $this->getFactory()->createGridVoucherGrid(
            $this->getQueryContainer()->queryDiscountVoucher(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherPoolGrid
     */
    public function getVoucherPoolGrid(Request $request)
    {
        return $this->getFactory()->createGridVoucherGrid(
            $this->getQueryContainer()->queryDiscountVoucherPoolJoinedVoucherPoolCategory(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return VoucherPoolCategoryGrid
     */
    public function getVoucherPoolCategoryGrid(Request $request)
    {
        return $this->getFactory()->createGridVoucherGrid(
            $this->getQueryContainer()->queryDiscountVoucherPoolCategory(),
            $request
        );
    }

    /**
     * @return DiscountQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->discount()->queryContainer();
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
            ->findOneByIdDiscountVoucherPool($idPool)
        ;

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
            ->findOne()
        ;

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
            ->findOne()
        ;

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
            ->count()
        ;
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
            ->filterByFkDiscountVoucherPool($idPool)
        ;
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

}
