<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentAuthorizationFailedTransfer;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentCanceledTransfer;
use Generated\Shared\Transfer\PaymentCancellationFailedTransfer;
use Generated\Shared\Transfer\PaymentCapturedTransfer;
use Generated\Shared\Transfer\PaymentCaptureFailedTransfer;
use Generated\Shared\Transfer\PaymentOverpaidTransfer;
use Generated\Shared\Transfer\PaymentUnderpaidTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PaymentApp\PaymentAppConfig getConfig()
 * @method \Spryker\Zed\PaymentApp\Communication\PaymentAppCommunicationFactory getFactory()
 */
class PaymentAppOperationsMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     * - Each message handled will result in a call to the callable with the message as the first argument.
     * - Each message will either create or update a PaymentAppPaymentStatus entity.
     * - Each message will create a PaymentAppPaymentStatusHistory entity.
     *
     * @api
     *
     * @return iterable
     */
    public function handles(): iterable
    {
        return [
            PaymentCanceledTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentCancellationFailedTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentCapturedTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentCaptureFailedTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentAuthorizedTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentAuthorizationFailedTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentOverpaidTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
            PaymentUnderpaidTransfer::class => [$this->getFacade(), 'savePaymentAppPaymentStatus'],
        ];
    }
}
