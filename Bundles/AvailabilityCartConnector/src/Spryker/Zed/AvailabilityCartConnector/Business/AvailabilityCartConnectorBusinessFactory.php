<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business;

use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider;
use Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculator;
use Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Cart\CheckCartAvailability;
use Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreator;
use Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Filter\CartChangeItemFilter;
use Spryker\Zed\AvailabilityCartConnector\Business\Filter\CartChangeItemFilterInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReader;
use Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToMessengerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class AvailabilityCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Cart\CheckCartAvailabilityInterface
     */
    public function createCartCheckAvailability()
    {
        return new CheckCartAvailability(
            $this->createItemQuantityCalculator(),
            $this->createSellableItemsReader(),
            $this->createMessageCreator(),
            $this->getAvailabilityFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Filter\CartChangeItemFilterInterface
     */
    public function createCartChangeItemFilter(): CartChangeItemFilterInterface
    {
        return new CartChangeItemFilter(
            $this->createSellableItemsReader(),
            $this->createMessageCreator(),
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface
     */
    public function createSellableItemsReader(): SellableItemsReaderInterface
    {
        return new SellableItemsReader(
            $this->createItemQuantityCalculator(),
            $this->getAvailabilityFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface
     */
    public function createItemQuantityCalculator(): ItemQuantityCalculatorInterface
    {
        return new ItemQuantityCalculator($this->getCartItemQuantityCounterStrategyPlugins());
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface
     */
    public function createMessageCreator(): MessageCreatorInterface
    {
        return new MessageCreator();
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade(): AvailabilityCartConnectorToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return list<\Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface>
     */
    public function getCartItemQuantityCounterStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY);
    }
}
