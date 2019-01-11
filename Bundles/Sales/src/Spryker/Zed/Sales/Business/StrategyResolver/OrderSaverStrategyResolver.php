<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToSalesServiceInterface;

class OrderSaverStrategyResolver implements OrderSaverStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToSalesServiceInterface
     */
    protected $service;

    /**
     * @var \Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToSalesServiceInterface $service
     * @param array|\Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface[] $strategyContainer
     */
    public function __construct(SalesToSalesServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface
     */
    public function resolveByQuote(QuoteTransfer $quoteTransfer): SalesOrderSaverInterface
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