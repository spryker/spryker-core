<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodTransfer;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;
use Spryker\Glue\PaymentsRestApi\Processor\Exception\PaymentMethodNotConfiguredException;

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
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapRestCheckoutDataTransferToRestPaymentMethodsAttributesTransfers(
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): array {
        $result = [];

        foreach ($restCheckoutDataTransfer->getAvailablePaymentMethods()->getMethods() as $paymentMethod) {
            if (!$paymentMethod->getPaymentProvider()) {
                continue;
            }
            $restPaymentMethod = $this->createRestPaymentMethodAttributesTransfer($paymentMethod->getPaymentProvider(), $paymentMethod);
            $result[$paymentMethod->getIdPaymentMethod()] = $restPaymentMethod;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getPayments()->count()) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        $paymentProviders = $restCheckoutDataTransfer->getPaymentProviders()->getPaymentProviders();
        foreach ($paymentProviders as $paymentProviderTransfer) {
            $isPaymentProviderRequested = $this->isPaymentProviderRequested(
                $restCheckoutRequestAttributesTransfer,
                $paymentProviderTransfer
            );

            if (!$isPaymentProviderRequested) {
                continue;
            }

            $restCheckoutDataResponseAttributesTransfer = $this->addSelectedPaymentMethodToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataResponseAttributesTransfer,
                $paymentProviderTransfer,
                $restCheckoutRequestAttributesTransfer
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return bool
     */
    protected function isPaymentProviderRequested(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        PaymentProviderTransfer $paymentProviderTransfer
    ): bool {
        foreach ($restCheckoutRequestAttributesTransfer->getPayments() as $restPaymentTransfer) {
            if ($restPaymentTransfer->getPaymentProviderName() === $paymentProviderTransfer->getPaymentProviderKey()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function addSelectedPaymentMethodToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        PaymentProviderTransfer $paymentProviderTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $isPaymentMethodRequested = $this->isPaymentMethodRequested(
                $restCheckoutRequestAttributesTransfer,
                $paymentMethodTransfer
            );
            if (!$isPaymentMethodRequested) {
                continue;
            }

            $restCheckoutDataResponseAttributesTransfer->addSelectedPaymentMethod(
                $this->createRestPaymentMethodTransfer(
                    $paymentMethodTransfer,
                    $paymentProviderTransfer
                )
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodRequested(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): bool {
        foreach ($restCheckoutRequestAttributesTransfer->getPayments() as $restPaymentTransfer) {
            if ($paymentMethodTransfer->getName() === $restPaymentTransfer->getPaymentMethodName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodTransfer
     */
    protected function createRestPaymentMethodTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentProviderTransfer $paymentProviderTransfer
    ): RestPaymentMethodTransfer {
        return (new RestPaymentMethodTransfer())
            ->setPaymentMethodName($paymentMethodTransfer->getName())
            ->setPaymentProviderName($paymentProviderTransfer->getPaymentProviderKey())
            ->setRequiredRequestData(
                $this->config->getRequiredRequestDataForPaymentMethod($paymentProviderTransfer->getName(), $paymentMethodTransfer->getName())
            );
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
        $paymentMethodName = $paymentMethodTransfer->getName();
        $paymentMethodKey = $paymentMethodTransfer->getMethodName();
        $paymentProviderName = $paymentProviderTransfer->getPaymentProviderKey();

        return (new RestPaymentMethodsAttributesTransfer())
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderName)
            ->setPriority($this->config->getPaymentMethodPriority()[$paymentMethodKey] ?? null)
            ->setRequiredRequestData($this->config->getRequiredRequestDataForPaymentMethod($paymentProviderName, $paymentMethodKey));
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
