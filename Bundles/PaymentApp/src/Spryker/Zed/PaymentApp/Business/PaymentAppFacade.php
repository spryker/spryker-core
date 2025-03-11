<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusCriteriaTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppBusinessFactory getFactory()
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppRepositoryInterface getRepository()
 */
class PaymentAppFacade extends AbstractFacade implements PaymentAppFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        return $this->getFactory()
            ->createExpressCheckoutPaymentRequestExecutor()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer): PaymentCustomerResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentCustomer()
            ->getCustomer($paymentCustomerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->getFactory()->createPreOrderPayment()->initializePreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function confirmPreOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $this->getFactory()->createPreOrderPayment()->confirmPreOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->getFactory()->createPreOrderPayment()->cancelPreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $paymentAppMessage
     *
     * @return void
     */
    public function savePaymentAppPaymentStatus(AbstractTransfer $paymentAppMessage): void
    {
        $this->getFactory()->createPaymentAppPaymentStatus()->savePaymentAppPaymentStatus($paymentAppMessage);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusCriteriaTransfer $paymentAppPaymentStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer
     */
    public function getPaymentAppPaymentStatusCollection(
        PaymentAppPaymentStatusCriteriaTransfer $paymentAppPaymentStatusCriteriaTransfer
    ): PaymentAppPaymentStatusCollectionTransfer {
        return $this->getFactory()->createPaymentAppPaymentStatusReader()->getPaymentAppPaymentStatusCollection($paymentAppPaymentStatusCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer
     */
    public function hasPaymentAppExpectedPaymentStatus(
        PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
    ): PaymentAppPaymentStatusResponseTransfer {
        return $this->getFactory()->createPaymentAppPaymentStatus()->hasPaymentAppExpectedPaymentStatus($paymentAppPaymentStatusRequestTransfer);
    }
}
