<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Shipment\Dependency\Service\ShipmentToSalesServiceInterface;

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
     * @var \Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Zed\Shipment\Dependency\Service\ShipmentToSalesServiceInterface $service
     * @param array|\Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface[] $strategyContainer
     */
    public function __construct(ShipmentToSalesServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface
     */
    public function resolveByQuote(QuoteTransfer $quoteTransfer): CalculatorInterface
    {
        if ($this->service->checkQuoteItemHasOwnShipmentTransfer($quoteTransfer) === false) {
            return $this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT];
        }

        return $this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT];
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function assertRequiredStrategyContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }

        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}