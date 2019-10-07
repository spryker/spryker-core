<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;

class PaymentMethodMapper implements PaymentMethodMapperInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig $config
     */
    public function __construct(PaymentsRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer[] $paymentProviderTransfers
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers(
        array $paymentProviderTransfers,
        array $restPaymentMethodsAttributesTransfers = []
    ): array {
        foreach ($paymentProviderTransfers as $paymentProviderTransfer) {
            $restPaymentMethodsAttributesTransfers += $this->mapPaymentProviderTransferToRestPaymentMethodAttributesTransfers($paymentProviderTransfer);
        }

        return $restPaymentMethodsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    protected function mapPaymentProviderTransferToRestPaymentMethodAttributesTransfers(
        PaymentProviderTransfer $paymentProviderTransfer,
        array $restPaymentMethodsAttributesTransfers = []
    ): array {
        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $restPaymentMethodsAttributesTransfers[$paymentMethodTransfer->getIdSalesPaymentMethodType()] =
                $this->createRestPaymentMethodAttributesTransfer($paymentProviderTransfer, $paymentMethodTransfer);
        }

        return $restPaymentMethodsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer
     */
    protected function createRestPaymentMethodAttributesTransfer(
        PaymentProviderTransfer $paymentProviderTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): RestPaymentMethodsAttributesTransfer {
        $paymentMethodName = $paymentMethodTransfer->getMethodName();
        $paymentProviderName = $paymentProviderTransfer->getName();

        return (new RestPaymentMethodsAttributesTransfer())
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderName)
            ->setPriority($this->config->getPaymentMethodPriority()[$paymentMethodName] ?? null)
            ->setRequiredRequestData($this->config->getRequiredRequestDataForPaymentMethod($paymentProviderName, $paymentMethodName));
    }
}
