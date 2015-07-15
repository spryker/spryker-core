<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountFacade getFacade()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function decisionRuleAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDecisionRuleForm($request);

        $form->init();

        if ($form->isValid()) {
            $discountDecisionRule = new DecisionRuleTransfer();
            $discountDecisionRule->fromArray($form->getRequestData());

            if (is_null($discountDecisionRule->getIdDiscountDecisionRule())) {
                $this->getFacade()->createDiscountDecisionRule($discountDecisionRule);
            } else {
                $this->getFacade()->updateDiscountDecisionRule($discountDecisionRule);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function discountAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDiscountForm($request);

        $form->init();

        if ($form->isValid()) {
            $discount = new DiscountTransfer();
            $discount->fromArray($form->getRequestData());

            if (is_null($discount->getIdDiscount())) {
                $this->getFacade()->createDiscount($discount);
            } else {
                $this->getFacade()->updateDiscount($discount);
            }
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucher = new VoucherTransfer();
            $voucher->fromArray($form->getRequestData());

            if (is_null($voucher->getIdDiscountVoucher())) {
                $this->getFacade()->createDiscountVoucher($voucher);
            } else {
                $this->getFacade()->updateDiscountVoucher($voucher);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherPoolCategoryAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherPoolCategoryForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucherPoolCategory = new VoucherPoolCategoryTransfer();
            $voucherPoolCategory->fromArray($form->getRequestData());

            if (is_null($voucherPoolCategory->getIdDiscountVoucherPoolCategory())) {
                $this->getFacade()->createDiscountVoucherPoolCategory($voucherPoolCategory);
            } else {
                $this->getFacade()->updateDiscountVoucherPoolCategory($voucherPoolCategory);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherPoolAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getVoucherPoolForm($request);

        $form->init();

        if ($form->isValid()) {
            $voucherPool = new VoucherPoolTransfer();
            $voucherPool->fromArray($form->getRequestData());

            if (is_null($voucherPool->getIdDiscountVoucherPool())) {
                $discountVoucherPoolEntity = $this->getFacade()->createDiscountVoucherPool($voucherPool);
            } else {
                $discountVoucherPoolEntity = $this->getFacade()->updateDiscountVoucherPool($voucherPool);
            }

            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

}
