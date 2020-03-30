<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestPaymentMethodTransfer;
use Generated\Shared\Transfer\RestPaymentProviderTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Exception\PaymentMethodNotConfiguredException;

class PaymentProviderCheckoutDataResponseMapper implements CheckoutDataResponseMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $config
     */
    public function __construct(CheckoutRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function map(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if ($this->config->isPaymentProvidersMappedToAttributes()) {
            $restCheckoutDataResponseAttributesTransfer = $this->mapPaymentProviders(
                $restCheckoutDataTransfer,
                $restCheckoutDataResponseAttributesTransfer
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapPaymentProviders(
        RestCheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $availablePaymentMethodsList = $this->getAvailablePaymentMethodsList($checkoutDataTransfer->getAvailablePaymentMethods());

        foreach ($checkoutDataTransfer->getPaymentProviders()->getPaymentProviders() as $paymentProviderTransfer) {
            $restPaymentProviderTransfer = new RestPaymentProviderTransfer();
            $restPaymentProviderTransfer->setPaymentProviderName($paymentProviderTransfer->getPaymentProviderKey());

            foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
                $paymentSelection = $this->findPaymentSelectionByPaymentProviderAndMethodNames(
                    $paymentProviderTransfer->getPaymentProviderKey(),
                    $paymentMethodTransfer->getName()
                );

                if (!$paymentSelection || !in_array($paymentMethodTransfer->getMethodName(), $availablePaymentMethodsList)) {
                    continue;
                }

                $restPaymentMethodTransfer = (new RestPaymentMethodTransfer())
                    ->setPaymentMethodName($paymentMethodTransfer->getName())
                    ->setRequiredRequestData(
                        $this->config->getRequiredRequestDataForPaymentMethod($paymentMethodTransfer->getMethodName())
                    );

                $restPaymentProviderTransfer->addPaymentMethod($restPaymentMethodTransfer);
            }
            $restCheckoutDataResponseAttributesTransfer->addPaymentProvider($restPaymentProviderTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
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
     * @return string|null
     */
    protected function findPaymentSelectionByPaymentProviderAndMethodNames(string $paymentProviderName, string $paymentMethodName): ?string
    {
        if (!$this->config->isPaymentProviderMethodToStateMachineMappingEnabled()) {
            return $paymentMethodName;
        }

        $paymentProviderMethodToStateMachineMapping = $this->config->getPaymentProviderMethodToStateMachineMapping();
        if (!isset($paymentProviderMethodToStateMachineMapping[$paymentProviderName][$paymentMethodName])) {
            throw new PaymentMethodNotConfiguredException(sprintf(
                'Payment method "%s" for payment provider "%s" is not configured in CheckoutRestApiConfig::getPaymentProviderMethodToStateMachineMapping()',
                $paymentMethodName,
                $paymentProviderName
            ));
        }

        return $paymentProviderMethodToStateMachineMapping[$paymentProviderName][$paymentMethodName];
    }
}
