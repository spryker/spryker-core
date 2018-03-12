<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\ProductReservationReader;
use Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreCondition;
use Spryker\Zed\Availability\Business\Model\Sellable;
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
    public function createSellableModel()
    {
        return new Sellable(
            $this->getOmsFacade(),
            $this->getStockFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    public function createAvailabilityHandler()
    {
        return new AvailabilityHandler(
            $this->createSellableModel(),
            $this->getStockFacade(),
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\ProductReservationReaderInterface
     */
    public function createProductReservationReader()
    {
        return new ProductReservationReader(
            $this->getQueryContainer(),
            $this->getStockFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected function getStockFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreConditionInterface
     */
    public function createProductsAvailablePreCondition()
    {
        return new ProductsAvailableCheckoutPreCondition($this->createSellableModel(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STORE);
    }
}
