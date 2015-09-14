<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\PayonePaymentDetailTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesQueryContainerInterface getQueryContainer()
 * @method SalesFacade getFacade()
 * @deprecated ?
 */
class RefundController extends AbstractController
{

    const SALES_ORDER_DETAIL_URL = '/sales/details?id-sales-order=%d';
    //const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idOrder)
            ->findOne();
        if (null === $orderEntity) {
            throw new NotFoundHttpException();
        }

        $shippingAddress = $this->getQueryContainer()
            ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressShipping())
            ->findOne();
        if ($orderEntity->getFkSalesOrderAddressShipping() === $orderEntity->getFkSalesOrderAddressBilling()) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = $this->getQueryContainer()
                ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressBilling())
                ->findOne();
        }

        /** @var SpyPaymentPayone $paymentPayoneEntity */
        $paymentPayoneEntity = $orderEntity->getSpyPaymentPayones()->getFirst();
        $idPayment = $paymentPayoneEntity->getIdPaymentPayone();

        $form = $this->getDependencyContainer()
            ->createPaymentDetailForm($idPayment)
        ;
        $form->handleRequest();

        if ($form->isValid()) {

            $paymentDetailTransfer = (new PayonePaymentDetailTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                ->updatePaymentDetail($paymentDetailTransfer, $idPayment)
            ;

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        $data = $form->getData();

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            //'orderItems' => $orderItems,
            'form' => $form->createView(),
        ];
    }

}
