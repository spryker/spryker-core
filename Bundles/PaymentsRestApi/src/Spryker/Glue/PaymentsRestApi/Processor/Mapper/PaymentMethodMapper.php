<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\Processor\Exception\PaymentMethodNotConfiguredException;
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
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapRestCheckoutDataTransferToRestPaymentMethodsAttributesTransfers(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        array $restPaymentMethodsAttributesTransfers = []
    ): array {
        $paymentProviderTransfers = $restCheckoutDataTransfer->getPaymentProviders()->getPaymentProviders() ?? [];
        $availablePaymentMethodsList = $this->getAvailablePaymentMethodsList($restCheckoutDataTransfer->getAvailablePaymentMethods());

        foreach ($paymentProviderTransfers as $paymentProviderTransfer) {
            foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
                $paymentSelection = $this->getPaymentSelectionByPaymentProviderAndMethodNames($paymentProviderTransfer->getName(), $paymentMethodTransfer->getMethodName());
                if (in_array($paymentSelection, $availablePaymentMethodsList)) {
                    $restPaymentMethodsAttributesTransfers[$paymentMethodTransfer->getIdSalesPaymentMethodType()] =
                        $this->createRestPaymentMethodAttributesTransfer($paymentProviderTransfer, $paymentMethodTransfer);
                }
            }
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

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $availablePaymentMethods
     *
     * @return array
     */
    protected function getAvailablePaymentMethodsList(PaymentMethodsTransfer $availablePaymentMethods): array
    {
        $availablePaymentMethodsList = [];
        foreach ($availablePaymentMethods->getMethods() as $paymentMethodTransfer) {
            $availablePaymentMethodsList[] = $paymentMethodTransfer->getMethodName();
        }

        return $availablePaymentMethodsList;
    }

    /**
     * @param string $paymentProviderName
     * @param string $paymentMethodName
     *
     * @throws \Spryker\Glue\CheckoutRestApi\Processor\Exception\PaymentMethodNotConfiguredException
     *
     * @return string
     */
    protected function getPaymentSelectionByPaymentProviderAndMethodNames(string $paymentProviderName, string $paymentMethodName): string
    {
        $paymentProviderMethodToPaymentSelectionMapping = $this->config->getPaymentProviderMethodToPaymentSelectionMapping();

        if (!isset($paymentProviderMethodToPaymentSelectionMapping[$paymentProviderName][$paymentMethodName])) {
            throw new PaymentMethodNotConfiguredException(sprintf(
                'Payment method "%s" for payment provider "%s" is not configured in PaymentsRestApiConfig::getPaymentProviderMethodToPaymentSelectionMapping()',
                $paymentMethodName,
                $paymentProviderName
            ));
        }

        return $paymentProviderMethodToPaymentSelectionMapping[$paymentProviderName][$paymentMethodName];
    }
}
