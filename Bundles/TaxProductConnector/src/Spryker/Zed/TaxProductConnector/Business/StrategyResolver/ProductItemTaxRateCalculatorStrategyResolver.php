<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\StrategyResolver;

use ArrayObject;
use Closure;
use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class ProductItemTaxRateCalculatorStrategyResolver implements ProductItemTaxRateCalculatorStrategyResolverInterface
{
    /**
     * @var string
     */
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';

    /**
     * @var string
     */
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @var array<\Closure>
     */
    protected array $strategyContainer;

    /**
     * @var array<\Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin\ShippingAddressValidatorPluginInterface>
     */
    protected array $shippingAddressValidatorPlugins;

    /**
     * @param array<\Closure> $strategyContainer
     * @param array<\Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin\ShippingAddressValidatorPluginInterface> $shippingAddressValidatorPlugins
     */
    public function __construct(array $strategyContainer, array $shippingAddressValidatorPlugins)
    {
        $this->strategyContainer = $strategyContainer;
        $this->shippingAddressValidatorPlugins = $shippingAddressValidatorPlugins;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function resolve(ArrayObject $itemTransfers, ?AddressTransfer $shippingAddressTransfer): CalculatorInterface
    {
        if ($this->isShippingAddressValid($shippingAddressTransfer)) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems();

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                $this->assertRequiredStrategyWithoutMultiShipmentContainerItems();

                return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
            }
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems();

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithoutMultiShipmentContainerItems(): void
    {
        if (
            !isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithMultiShipmentContainerItems(): void
    {
        if (
            !isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return bool
     */
    protected function isShippingAddressValid(?AddressTransfer $shippingAddressTransfer): bool
    {
        if ($shippingAddressTransfer === null) {
            return false;
        }

        return $this->executeShippingAddressValidatorPlugins($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return bool
     */
    protected function executeShippingAddressValidatorPlugins(AddressTransfer $shippingAddressTransfer): bool
    {
        foreach ($this->shippingAddressValidatorPlugins as $shippingAddressValidatorPlugin) {
            if ($shippingAddressValidatorPlugin->isValid($shippingAddressTransfer)) {
                return true;
            }
        }

        return false;
    }
}
