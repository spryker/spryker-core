<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Business;

use Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreCondition;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer getQueryContainer()
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
            $this->getStockFacade()
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
     * @return \Spryker\Zed\Availability\Business\Model\ProductsAvailableCheckoutPreCondition
     */
    public function createProductsAvailablePreCondition()
    {
        return new ProductsAvailableCheckoutPreCondition($this->getSellableModel());
    }

}
