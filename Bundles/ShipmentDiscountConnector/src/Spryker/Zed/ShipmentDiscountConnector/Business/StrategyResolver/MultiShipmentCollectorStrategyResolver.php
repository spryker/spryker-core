<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface;
use Closure;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
class MultiShipmentCollectorStrategyResolver implements MultiShipmentCollectorStrategyResolverInterface
{
    /**
     * @var array|Closure[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param array|Closure[] $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function resolveByType(string $type): ShipmentDiscountCollectorInterface
    {
        if (!defined('\Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer::FK_SALES_SHIPMENT')) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems($type);

            return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems($type);

        return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
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