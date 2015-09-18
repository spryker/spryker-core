<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Controller;

use Generated\Shared\Transfer\RefundTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Communication\RefundDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method RefundDependencyContainer getDependencyContainer()
 * @method RefundFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createRefundsTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $idSalesOrder = $request->query->get('id-sales-order');

        $form = $this->getDependencyContainer()
            ->createRefundForm($request)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();

            $orderTransfer = $this->getFacade()->getOrderByIdSalesOrder($idSalesOrder);

            $refundTransfer = (new RefundTransfer())->fromArray($formData, true);
            $refundTransfer->setFkSalesOrder($orderTransfer);

            $this->getFacade()->saveRefund($refundTransfer);
            $this->addSuccessMessage('Refund successfully saved');

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idSalesOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }
}
