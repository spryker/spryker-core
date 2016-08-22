<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    public function paymentInit($quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);
        $this->mapQuoteHeadData($quoteTransfer, $paymentData);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRequest($quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);
        $this->mapQuoteHeadData($quoteTransfer, $paymentData);
        $this->mapPaymentData($quoteTransfer, $paymentData);

        return $request;
    }

    /**
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
     * @return mixed
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
     * @return mixed
     */
    abstract protected function getPaymentTransferObject($payment);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface
     *
     * @return void
     */
    protected function mapQuoteHeadData($quoteTransfer, $paymentData)
    {
        $this->mapperFactory
            ->getQuoteHeadMapper($quoteTransfer, $paymentData)
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
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     *
     * @return void
     */
    protected function mapShoppingBasketAndItems($dataTransfer, $paymentData)
    {
        $this->mapperFactory
            ->getBasketMapper($dataTransfer, $paymentData)
            ->map();

        $basketItems = $dataTransfer->requireItems()->getItems();
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     *
     * @return void
     */
    protected function mapPaymentData($quoteTransfer, $paymentData)
    {
        $this->mapperFactory
            ->getPaymentMapper($quoteTransfer, $paymentData)
            ->map();

        $this->mapperFactory
            ->getCustomerMapper($quoteTransfer, $paymentData)
            ->map();

        $this->mapShoppingBasketAndItems($quoteTransfer, $paymentData);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentData
     *
     * @return void
     */
    protected function mapBankAccountData($quoteTransfer, $paymentData)
    {
        $this->mapperFactory
            ->getBankAccountMapper($quoteTransfer, $paymentData)
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
