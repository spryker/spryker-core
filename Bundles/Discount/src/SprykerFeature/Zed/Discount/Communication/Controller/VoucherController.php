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

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class VoucherController extends AbstractController
{

    const NR_VOUCHERS = 1;

    /**
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        $form = $this->getDependencyContainer()->createFormVoucherForm();
        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->getFacade()->createVoucherCodes(self::NR_VOUCHERS, $formData[VoucherForm::FIELD_POOL], false);

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
        $table = $this->getDependencyContainer()->createVoucherTable();

        return [
            'vouchers' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createVoucherTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
