<?php

namespace Spryker\Zed\AvailabilityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageBusinessFactory getFactory()
 */
class AvailabilityStorageFacade extends AbstractFacade implements AvailabilityStorageFacadeInterface
{

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds)
    {
        $this->getFactory()->createAvailabilityStorage()->publish($availabilityIds);
    }

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds)
    {
        $this->getFactory()->createAvailabilityStorage()->unpublish($availabilityIds);
    }

}
