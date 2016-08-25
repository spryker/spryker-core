<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface;
use Spryker\Zed\Ratepay\Business\Request\RequestMethodInterface;
use \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

abstract class AbstractMethod implements MethodInterface, RequestMethodInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected $modelFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface $modelFactory
     * @param \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory $mapperFactory
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     *
     */
    public function __construct(
        RequestModelFactoryInterface $modelFactory,
        MapperFactory $mapperFactory,
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->modelFactory = $modelFactory;
        $this->mapperFactory = $mapperFactory;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    public function paymentInit(RatepayPaymentInitTransfer $ratepayPaymentInitTransfer)
    {
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);
        $this->mapPaymentInitHeadData($ratepayPaymentInitTransfer);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRequest(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);
        $this->mapPaymentInitHeadData($ratepayPaymentRequestTransfer->getRatepayPaymentInit());
        $this->mapPaymentData($ratepayPaymentRequestTransfer);

        return $request;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm
     */
    public function paymentConfirm(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM);
        $this->mapOrderHeadData($orderTransfer, $payment);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function deliveryConfirm(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM);

        $this->mapOrderHeadData($orderTransfer, $payment);
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentCancel(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL);

        $this->mapOrderHeadData($orderTransfer, $payment);
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRefund(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REFUND);

        $this->mapOrderHeadData($orderTransfer, $payment);
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function configurationRequest(QuoteTransfer $quoteTransfer)
    {

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function calculationRequest(QuoteTransfer $quoteTransfer)
    {

    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getTransferObjectFromPayment($payment)
    {
        $paymentTransfer = $this->getPaymentTransferObject($payment);
        $paymentTransfer->fromArray($payment->toArray(), true);

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    abstract protected function getPaymentTransferObject($payment);

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     *
     * @return void
     */
    protected function mapPaymentInitHeadData(RatepayPaymentInitTransfer $ratepayPaymentInitTransfer)
    {
        $this->mapperFactory
            ->getPaymentInitHeadMapper($ratepayPaymentInitTransfer)
            ->map();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return void
     */
    protected function mapOrderHeadData($orderTransfer, $payment)
    {
        $this->mapperFactory
            ->getOrderHeadMapper($orderTransfer, $payment)
            ->map();
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return void
     */
    protected function mapShoppingBasketAndItems(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        $this->mapperFactory
            ->getBasketMapper($ratepayPaymentRequestTransfer)
            ->map();

        $basketItems = $ratepayPaymentRequestTransfer->getItems();
        foreach ($basketItems as $basketItem) {
            $this->mapperFactory
                ->getBasketItemMapper($basketItem)
                ->map();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     *
     * @return void
     */
    protected function mapPartialShoppingBasketAndItems($dataTransfer, $paymentData, array $orderItems)
    {
        $this->mapperFactory
            ->getPartialBasketMapper($dataTransfer, $paymentData, $orderItems)
            ->map();

        foreach ($orderItems as $basketItem) {
            $this->mapperFactory
                ->getBasketItemMapper($basketItem)
                ->map();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return void
     */
    protected function mapPaymentData(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        $this->mapperFactory
            ->getPaymentMapper($ratepayPaymentRequestTransfer)
            ->map();

        $this->mapperFactory
            ->getCustomerMapper($ratepayPaymentRequestTransfer)
            ->map();

        $this->mapShoppingBasketAndItems($ratepayPaymentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return void
     */
    protected function mapBankAccountData(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        $this->mapperFactory
            ->getBankAccountMapper($ratepayPaymentRequestTransfer)
            ->map();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function loadOrderPayment(OrderTransfer $orderTransfer)
    {
        return $this->queryContainer
            ->queryPayments()
            ->findByFkSalesOrder(
                $orderTransfer->requireIdSalesOrder()->getIdSalesOrder()
            )->getFirst();
    }

}
