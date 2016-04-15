<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\RefundExpenseTransfer;
use Generated\Shared\Transfer\RefundOrderItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createRefundTable($this->getFacade());

        return $this->viewResponse(['refunds' => $table->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createRefundTable($this->getFacade());

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $idOrder = $this->castId($request->query->get('id-sales-order'));

        $orderTransfer = $this->getFactory()->getSalesAggregatorFacade()->getOrderTotalsByIdSalesOrder($idOrder);

        $orderItems = $this->getFacade()->getRefundableItems($idOrder);

        if ($orderItems->count() < 1) {
            $this->addErrorMessage('Nothing to refund.');

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        $expenses = $this->getFacade()->getRefundableExpenses($idOrder);

        $form = $this->getFactory()
            ->createRefundForm($orderTransfer, $this->getFacade());

        $form->handleRequest();

        $payoneFacade = $this->getFactory()->getPayoneFacade();
        $isPaymentDataRequired = $payoneFacade->isPaymentDataRequired($orderTransfer);

        if ($form->isValid()) {
            $formData = $form->getData();

            if ($isPaymentDataRequired) {
                $paymentDataTransfer = new PaymentDataTransfer();
                $paymentDataTransfer = $paymentDataTransfer->fromArray($formData, true);
                $this->getFactory()
                    ->getPayoneFacade()
                    ->updatePaymentDetail($paymentDataTransfer, $idOrder);
            }

            $refundTransfer = new RefundTransfer();
            $refundTransfer->fromArray($formData, true);
            $refundTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());

            foreach ($formData['order_items'] as $key => $quantity) {
                $orderItemTransfer = new RefundOrderItemTransfer();
                $orderItemTransfer->setIdOrderItem($key);
                $orderItemTransfer->setQuantity($quantity);
                $refundTransfer->addOrderItem($orderItemTransfer);
            }

            foreach ($formData['expenses'] as $key => $quantity) {
                $expenseTransfer = new RefundExpenseTransfer();
                $expenseTransfer->setIdExpense($key);
                $expenseTransfer->setQuantity($quantity);
                $refundTransfer->addExpense($expenseTransfer);
            }

            $this->getFacade()->saveRefund($refundTransfer);

            $this->addSuccessMessage('Refund successfully started');

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        $orderItemsArray = [];
        foreach ($orderItems as $orderItem) {
            $orderItemsArray[$orderItem->getIdSalesOrderItem()] = $orderItem;
        }
        $expensesArray = [];
        foreach ($expenses as $expense) {
            $expensesArray[$expense->getIdSalesExpense()] = $expense;
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'maxRefundAmount' => $this->getFacade()->calculateRefundableAmount($orderTransfer),
            'form' => $form->createView(),
            'orderItems' => $orderItemsArray,
            'expenses'=> $expensesArray,
            'isPaymentDataRequired' => $isPaymentDataRequired,
        ]);
    }

}
