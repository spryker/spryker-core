<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;

class PaymentMethodsMapper implements PaymentMethodsMapperInterface
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
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers(
        array $paymentProviderTransfers
    ): array {
        $restPaymentMethodsAttributesTransfers = [];

        foreach ($paymentProviderTransfers as $paymentProviderTransfer) {
            $restPaymentMethodsAttributesTransfers +=
                $this->mapPaymentMethodsTransfersToRestPaymentMethodAttributesTransfers(
                    $paymentProviderTransfer,
                    $paymentProviderTransfer->getPaymentMethods()
                );
        }

        return $restPaymentMethodsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentMethodTransfer[] $paymentMethodTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    protected function mapPaymentMethodsTransfersToRestPaymentMethodAttributesTransfers(
        PaymentProviderTransfer $paymentProviderTransfer,
        ArrayObject $paymentMethodTransfers
    ): array {
        $restPaymentMethodsAttributesTransfers = [];

        foreach ($paymentMethodTransfers as $paymentMethodTransfer) {
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
        $paymentMethodPriority = $this->findPaymentMethodPriorityInConfig($paymentMethodName);

        return (new RestPaymentMethodsAttributesTransfer())
            ->setName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderTransfer->getName())
            ->setPriority($paymentMethodPriority);
    }

    /**
     * @param string $paymentMethodName
     *
     * @return int|null
     */
    protected function findPaymentMethodPriorityInConfig(string $paymentMethodName): ?int
    {
        $paymentMethodPriorityMap = $this->config->getPaymentMethodPriority();

        return $paymentMethodPriorityMap[$paymentMethodName] ?? null;
    }
}
