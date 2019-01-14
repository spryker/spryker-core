<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
class CartExpanderStrategyResolver implements CartExpanderStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface
     */
    protected $service;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface $service
     * @param array|\Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface[] $strategyContainer
     */
    public function __construct(ShipmentCartConnectorToShipmentServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface
     */
    public function resolveByQuote(QuoteTransfer $quoteTransfer): ShipmentCartExpanderInterface
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