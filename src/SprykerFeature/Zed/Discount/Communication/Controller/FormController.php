<?php

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function decisionRuleAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDecisionRuleForm($request);

        $form->init();

        if ($form->isValid()) {
            $discountDecisionRule = $this->getLocator()->discount()->transferDiscountDecisionRule();
            $discountDecisionRule->fromArray($form->getRequestData());

            if (is_null($discountDecisionRule->getIdDiscountDecisionRule())) {
                $this->getDiscountFacade()->createDiscountDecisionRule($discountDecisionRule);
            } else {
                $this->getDiscountFacade()->updateDiscountDecisionRule($discountDecisionRule);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function discountAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDiscountForm($request);

        $form->init();

        if ($form->isValid()) {
            $discount = $this->getLocator()->discount()->transferDiscount();
            $discount->fromArray($form->getRequestData());

            if (is_null($discount->getIdDiscount())) {
                $this->getDiscountFacade()->createDiscount($discount);
            } else {
                $this->getDiscountFacade()->updateDiscount($discount);
            }
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function voucherAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucher = $this->getLocator()->discount()->transferDiscountVoucher();
            $voucher->fromArray($form->getRequestData());

            if (is_null($voucher->getIdDiscountVoucher())) {
                $this->getDiscountFacade()->createDiscountVoucher($voucher);
            } else {
                $this->getDiscountFacade()->updateDiscountVoucher($voucher);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function voucherPoolCategoryAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherPoolCategoryForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucherPoolCategory = $this->getLocator()->discount()->transferDiscountVoucherPoolCategory();
            $voucherPoolCategory->fromArray($form->getRequestData());

            if (is_null($voucherPoolCategory->getIdDiscountVoucherPoolCategory())) {
                $this->getDiscountFacade()->createDiscountVoucherPoolCategory($voucherPoolCategory);
            } else {
                $this->getDiscountFacade()->updateDiscountVoucherPoolCategory($voucherPoolCategory);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function voucherPoolAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherPoolForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucherPool = $this->getLocator()->discount()->transferDiscountVoucherPool();
            $voucherPool->fromArray($form->getRequestData());

            if (is_null($voucherPool->getIdDiscountVoucherPool())) {
                $discountVoucherPoolEntity = $this->getDiscountFacade()->createDiscountVoucherPool($voucherPool);
            } else {
                $discountVoucherPoolEntity = $this->getDiscountFacade()->updateDiscountVoucherPool($voucherPool);
            }

            $form->setActiveValuesToDefault($discountVoucherPoolEntity->toArray());
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @return DiscountFacade
     */
    protected function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }
}
