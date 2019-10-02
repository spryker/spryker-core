<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityReader;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface;
use Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreCondition;
use Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreConditionInterface;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    public function createSellableModel(): SellableInterface
    {
        return new Sellable(
            $this->getOmsFacade(),
            $this->getStockFacade(),
            $this->getStoreFacade(),
            $this->createProductAvailabilityReader()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    public function createAvailabilityHandler(): AvailabilityHandlerInterface
    {
        return new AvailabilityHandler(
            $this->createSellableModel(),
            $this->getStockFacade(),
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getStoreFacade(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface
     */
    public function createProductAvailabilityReader(): ProductAvailabilityReaderInterface
    {
        return new ProductAvailabilityReader(
            $this->getQueryContainer(),
            $this->getStockFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    public function getStockFacade(): AvailabilityToStockFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    public function getOmsFacade(): AvailabilityToOmsFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface
     */
    public function getTouchFacade(): AvailabilityToTouchInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface
     */
    public function getProductFacade(): AvailabilityToProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreConditionInterface
     */
    public function createProductsAvailablePreCondition(): ProductsAvailableCheckoutPreConditionInterface
    {
        return new ProductsAvailableCheckoutPreCondition($this->createSellableModel(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    public function getStoreFacade(): AvailabilityToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    public function getEventFacade(): AvailabilityToEventFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_EVENT);
    }
}
