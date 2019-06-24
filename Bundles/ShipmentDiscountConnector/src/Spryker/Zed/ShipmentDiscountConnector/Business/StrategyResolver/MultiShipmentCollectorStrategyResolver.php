<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Closure;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class MultiShipmentCollectorStrategyResolver implements MultiShipmentCollectorStrategyResolverInterface
{
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    public const DISCOUNT_TYPE_CARRIER = 'DISCOUNT_TYPE_CARRIER';
    public const DISCOUNT_TYPE_METHOD = 'DISCOUNT_TYPE_METHOD';
    public const DISCOUNT_TYPE_PRICE = 'DISCOUNT_TYPE_PRICE';

    /**
     * @var array
     */
    protected $strategyContainer;

    /**
     * @param array $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @param string $type
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function resolveByTypeAndItems(string $type, iterable $itemTransfers): ShipmentDiscountCollectorInterface
    {
        if (count($itemTransfers) === 0) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems($type);

            return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                $this->assertRequiredStrategyWithoutMultiShipmentContainerItems($type);

                return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
            }
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems($type);

        return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithoutMultiShipmentContainerItems(string $type): void
    {
        if (!isset($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithMultiShipmentContainerItems(string $type): void
    {
        if (!isset($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}
