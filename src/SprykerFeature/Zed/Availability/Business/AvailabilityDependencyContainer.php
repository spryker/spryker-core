<?php

namespace SprykerFeature\Zed\Availability\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use SprykerFeature\Zed\Availability\Business\Model\SellableInterface;

/**
 * Class AvailabilityDependencyContainer
 * @package SprykerFeature\Zed\Availability\Business
 */
class AvailabilityDependencyContainer extends AbstractDependencyContainer
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
        return $this->getLocator()->stock()->facade();
    }

    /**
     * @return AvailabilityToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getLocator()->oms()->facade();
    }

}
