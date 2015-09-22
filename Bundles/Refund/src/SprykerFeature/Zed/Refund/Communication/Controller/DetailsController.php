<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Communication\RefundDependencyContainer;
use SprykerFeature\Zed\Refund\Persistence\RefundQueryContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method RefundDependencyContainer getDependencyContainer()
 * @method RefundFacade getFacade()
 * @method RefundQueryContainer getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idRefund = $request->get('id-refund');

        $refundEntity = $this->getQueryContainer()
            ->queryRefundByIdRefund($idRefund)
            ->findOne();

        if ($refundEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getDependencyContainer()->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByFkRefund($idRefund)
            ->find();

        $expenses = $this->getDependencyContainer()->getSalesQueryContainer()
            ->querySalesExpense()
            ->filterByFkRefund($idRefund)
            ->find();

        return [
            'idRefund' => $idRefund,
            'refund' => $refundEntity,
            'orderItems' => $orderItems,
            'expenses' => $expenses,
        ];
    }

}
