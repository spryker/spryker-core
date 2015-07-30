<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCommunication;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolCategoryTable;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolTable;
use SprykerFeature\Zed\Discount\Communication\Form\PoolCategoryForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;

/**
 * @method DiscountCommunication getFactory()
 */
class DiscountDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @return VoucherForm
     */
    public function createFormVoucherForm()
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return $this->getFactory()->createFormVoucherForm($poolQuery);
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
     * @return VoucherPoolTable
     */
    public function createVoucherPoolTable()
    {
        $poolQuery = $this->getQueryContainer()->queryDiscountVoucherPool();

        return $this->getFactory()->createTableVoucherPoolTable($poolQuery);
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

        return $this->getFactory()->createFormPoolForm($poolQuery, $discountQuery, $idPool);
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

}
