<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Availability\AvailabilityDependencyProvider;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use SprykerFeature\Zed\Availability\Business\Model\SellableInterface;

/**
 * Class AvailabilityDependencyContainer
 */
class AvailabilityDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return SellableInterface
     */
    public function getSellableModel()
    {
        return $this->getFactory()->create(
            'Model\\Sellable',
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
