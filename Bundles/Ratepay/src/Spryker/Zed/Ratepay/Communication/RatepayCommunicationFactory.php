<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\OrderPaymentInitMapper;
use Spryker\Zed\Ratepay\Business\Api\Mapper\OrderPaymentRequestMapper;
use Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentInitMapper;
use Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentRequestMapper;
use Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor;
use Spryker\Zed\Ratepay\RatepayDependencyProvider;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor
     */
    protected $paymentMethodExtractor;

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToSalesAggregatorInterface
     */
    public function getSalesAggregator()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_SALES_AGGREGATOR);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor
     */
    public function getPaymentMethodExtractor()
    {
        if (!$this->paymentMethodExtractor) {
            $this->paymentMethodExtractor = $this->createPaymentMethodExtractor();
        }

        return $this->paymentMethodExtractor;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor
     */
    protected function createPaymentMethodExtractor()
    {
        $paymentMethodsMap = [
            RatepayConstants::METHOD_INVOICE => 'ratepayInvoice',
            RatepayConstants::METHOD_ELV => 'ratepayElv',
            RatepayConstants::METHOD_INSTALLMENT => 'ratepayInstallment',
            RatepayConstants::METHOD_PREPAYMENT => 'ratepayPrepayment',
        ];
        return new PaymentMethodExtractor($paymentMethodsMap);
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentInitMapper
     */
    public function createPaymentInitMapperByQuote(
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        QuoteTransfer $quoteTransfer
    ) {

        return new QuotePaymentInitMapper(
            $ratepayPaymentInitTransfer,
            $quoteTransfer,
            $this->getPaymentMethodExtractor()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\OrderPaymentInitMapper
     */
    public function createPaymentInitMapperByOrder(
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        SpySalesOrder $orderEntity
    ) {

        return new OrderPaymentInitMapper(
            $ratepayPaymentInitTransfer,
            $orderEntity
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer $paymentData
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentRequestMapper
     */
    public function createPaymentRequestMapperByQuote(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        QuoteTransfer $quoteTransfer,
        $paymentData
    ) {

        return new QuotePaymentRequestMapper(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $quoteTransfer,
            $paymentData
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\OrderPaymentRequestMapper
     */
    public function createPaymentRequestMapperByOrder(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        OrderTransfer $orderTransfer,
        SpySalesOrder $orderEntity
    ) {

        return new OrderPaymentRequestMapper(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $orderTransfer,
            $orderEntity
        );
    }

}
