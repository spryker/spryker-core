<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface;
use Closure;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
class OrderSaverStrategyResolver implements OrderSaverStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Service\ShipmentToSalesServiceInterface
     */
    protected $service;

    /**
     * @var \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $service
     * @param array|\Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface[] $strategyContainer
     */
    public function __construct(ShipmentServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface
     */
    public function resolveByQuote(QuoteTransfer $quoteTransfer): ShipmentOrderSaverInterface
    {
        if ($this->service->checkQuoteItemHasOwnShipmentTransfer($quoteTransfer) === false) {
            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function assertRequiredStrategyContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }

        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}