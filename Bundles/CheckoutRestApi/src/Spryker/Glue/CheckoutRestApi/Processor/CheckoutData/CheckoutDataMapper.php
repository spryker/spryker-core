<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodTransfer;
use Generated\Shared\Transfer\RestPaymentProviderTransfer;
use Generated\Shared\Transfer\RestShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Exception\PaymentMethodNotConfiguredException;

class CheckoutDataMapper implements CheckoutDataMapperInterface
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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $restCheckoutDataResponseAttributesTransfer = new RestCheckoutDataResponseAttributesTransfer();

        $restCheckoutDataResponseAttributesTransfer = $this->mapRestAddressTransfer(
            $restCheckoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapPaymentProviders(
            $restCheckoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapShipmentMethods(
            $restCheckoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );

        $restCheckoutDataResponseAttributesTransfer = $this->mapSelectedPaymentMethods(
            $restCheckoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer,
            $restCheckoutRequestAttributesTransfer
        );

        $restCheckoutDataResponseAttributesTransfer = $this->mapSelectedShipmentMethods(
            $restCheckoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapRestAddressTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $addresses = $restCheckoutDataTransfer->getAddresses()->getAddresses();
        foreach ($addresses as $addressTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addAddress(
                (new RestAddressTransfer())->fromArray(
                    $addressTransfer->toArray(),
                    true
                )->setId($addressTransfer->getUuid())
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
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
        $paymentProviders = $checkoutDataTransfer->getPaymentProviders()->getPaymentProviders();
        foreach ($paymentProviders as $paymentProviderTransfer) {
            $restPaymentProviderTransfer = new RestPaymentProviderTransfer();
            $restPaymentProviderTransfer->setPaymentProviderName($paymentProviderTransfer->getName());

            foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
                $paymentSelection = $this->getPaymentSelectionByPaymentProviderAndMethodNames($restPaymentProviderTransfer->getPaymentProviderName(), $paymentMethodTransfer->getMethodName());
                if (in_array($paymentSelection, $availablePaymentMethodsList)) {
                    $restPaymentMethodTransfer = (new RestPaymentMethodTransfer())
                        ->setPaymentMethodName($paymentMethodTransfer->getMethodName());
                    $restPaymentMethodTransfer->setRequiredRequestData($this->config->getRequiredRequestDataForPaymentMethod($paymentSelection));
                    $restPaymentProviderTransfer->addPaymentMethod($restPaymentMethodTransfer);
                }
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
     * @deprecated Will be removed in next major release.
     *
     * @param string $paymentProviderName
     * @param string $paymentMethodName
     *
     * @throws \Spryker\Glue\CheckoutRestApi\Processor\Exception\PaymentMethodNotConfiguredException
     *
     * @return string
     */
    protected function getPaymentSelectionByPaymentProviderAndMethodNames(string $paymentProviderName, string $paymentMethodName): string
    {
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

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapShipmentMethods(
        RestCheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $shipmentMethodTransfers = $checkoutDataTransfer->getShipmentMethods()->getMethods();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodTransfer = $this->mapShipmentMethodTransferToRestShipmentMethodTransfer(
                $shipmentMethodTransfer,
                new RestShipmentMethodTransfer()
            );

            $restCheckoutDataResponseAttributesTransfer->addShipmentMethod($restShipmentMethodTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodTransfer $restShipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodTransfer
     */
    protected function mapShipmentMethodTransferToRestShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        RestShipmentMethodTransfer $restShipmentMethodTransfer
    ): RestShipmentMethodTransfer {
        $restShipmentMethodTransfer
            ->fromArray($shipmentMethodTransfer->toArray(), true)
            ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
            ->setId($shipmentMethodTransfer->getIdShipmentMethod());

        $moneyValueTransfer = current($shipmentMethodTransfer->getPrices());

        if (!$moneyValueTransfer) {
            return $restShipmentMethodTransfer;
        }

        $restShipmentMethodTransfer->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
        $restShipmentMethodTransfer->setDefaultNetPrice($moneyValueTransfer->getNetAmount());

        return $restShipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapSelectedShipmentMethods(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $shipmentMethodTransfers = $restCheckoutDataTransfer->getSelectedShipmentMethods()->getMethods();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodTransfer = $this->mapShipmentMethodTransferToRestShipmentMethodTransfer(
                $shipmentMethodTransfer,
                new RestShipmentMethodTransfer()
            );

            $restCheckoutDataResponseAttributesTransfer->addSelectedShipmentMethod($restShipmentMethodTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapSelectedPaymentMethods(
        RestCheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $availablePaymentMethodsList = $this->getAvailablePaymentMethodsList($checkoutDataTransfer->getAvailablePaymentMethods());

        $paymentProviders = $checkoutDataTransfer->getPaymentProviders()->getPaymentProviders();
        foreach ($paymentProviders as $paymentProviderTransfer) {
            $isPaymentProviderRequested = $this->isPaymentProviderRequested(
                $restCheckoutRequestAttributesTransfer,
                $paymentProviderTransfer
            );

            if (!$isPaymentProviderRequested) {
                continue;
            }

            $this->addSelectedPaymentMethodToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataResponseAttributesTransfer,
                $paymentProviderTransfer,
                $restCheckoutRequestAttributesTransfer,
                $availablePaymentMethodsList
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
            if ($restPaymentTransfer->getPaymentProviderName() === $paymentProviderTransfer->getName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param string[] $availablePaymentMethodsList
     *
     * @return void
     */
    protected function addSelectedPaymentMethodToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        PaymentProviderTransfer $paymentProviderTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        array $availablePaymentMethodsList
    ): void {
        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $isPaymentMethodExistsInRequestedPaymentMethods = $this->isPaymentMethodRequested(
                $restCheckoutRequestAttributesTransfer,
                $paymentMethodTransfer
            );
            if (!$isPaymentMethodExistsInRequestedPaymentMethods) {
                continue;
            }

            $paymentSelection = $this->getPaymentSelectionByPaymentProviderAndMethodNames(
                $paymentProviderTransfer->getName(),
                $paymentMethodTransfer->getMethodName()
            );

            if (in_array($paymentSelection, $availablePaymentMethodsList)) {
                $restCheckoutDataResponseAttributesTransfer->addSelectedPaymentMethod(
                    $this->createRestPaymentMethodTransfer(
                        $paymentMethodTransfer,
                        $paymentProviderTransfer,
                        $paymentSelection
                    )
                );
            }
        }
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
            if ($paymentMethodTransfer->getMethodName() === $restPaymentTransfer->getPaymentMethodName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodTransfer
     */
    protected function createRestPaymentMethodTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentProviderTransfer $paymentProviderTransfer,
        string $paymentSelection
    ): RestPaymentMethodTransfer {
        return (new RestPaymentMethodTransfer())
            ->setPaymentMethodName($paymentMethodTransfer->getMethodName())
            ->setPaymentProviderName($paymentProviderTransfer->getName())
            ->setRequiredRequestData(
                $this->config->getRequiredRequestDataForPaymentMethod($paymentSelection)
            );
    }
}
