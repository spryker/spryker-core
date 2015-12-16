<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Business;

use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Business\Model\SellableInterface;

/**
 * Class AvailabilityBusinessFactory
 */
class AvailabilityBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return SellableInterface
     */
    public function getSellableModel()
    {
        return new Sellable(
            $this->getOmsFacade(),
            $this->getStockFacade()
        );
    }

    /**
     * @return AvailabilityToStockFacadeInterface
     */
    protected function getStockFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return AvailabilityToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_OMS);
    }

}
