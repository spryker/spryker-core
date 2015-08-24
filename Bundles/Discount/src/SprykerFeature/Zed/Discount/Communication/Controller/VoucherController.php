<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Communication\Form\VoucherForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountFacade getFacade()
 */
class VoucherController extends AbstractController
{

    const NR_VOUCHERS = 1;

    /**
     * @return array|RedirectResponse
     */
    public function createSingleAction()
    {
        $form = $this->getDependencyContainer()->createVoucherForm();
        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->getFacade()->createVoucherCodes(
                self::NR_VOUCHERS,
                $formData[VoucherForm::FIELD_POOL],
                false,
                $formData[VoucherForm::FIELD_TITLE]
            );

            return $this->redirectResponse('/discount/voucher');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    public function createMultipleAction()
    {
        $form = $this->getDependencyContainer()->createVoucherForm(true);
        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->getFacade()->createVoucherCodes(
                $formData[VoucherForm::FIELD_NUMBER],
                $formData[VoucherForm::FIELD_POOL],
                false,
                $formData[VoucherForm::FIELD_TITLE]
            );

            return $this->redirectResponse('/discount/voucher');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function statusAction(Request $request)
    {
        $discountId = $request->request->get('id');
        $response = $this->getFacade()->toggleDiscountActiveStatus($discountId);

        return $this->jsonResponse($response);
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createDiscountVoucherTable();

        return [
            'vouchers' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createDiscountVoucherTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
