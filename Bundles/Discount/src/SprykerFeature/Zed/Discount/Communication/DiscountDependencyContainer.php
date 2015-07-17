<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCommunication;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\Form\DecisionRuleForm;
use SprykerFeature\Zed\Discount\Communication\Form\DiscountForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherPoolCategoryForm;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherPoolForm;
use SprykerFeature\Zed\Discount\Communication\Grid\DecisionRuleGrid;
use SprykerFeature\Zed\Discount\Communication\Grid\DiscountGrid;
use SprykerFeature\Zed\Discount\Communication\Grid\VoucherGrid;
use SprykerFeature\Zed\Discount\Communication\Grid\VoucherPoolCategoryGrid;
use SprykerFeature\Zed\Discount\Communication\Grid\VoucherPoolGrid;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DiscountCommunication getFactory()
 */
class DiscountDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return DiscountFacade
     */
    public function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
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
