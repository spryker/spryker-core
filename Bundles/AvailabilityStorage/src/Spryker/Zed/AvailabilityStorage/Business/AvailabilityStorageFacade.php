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
     * @param bool $sendingToQueue
     *
     * @return void
     */
    public function publish(array $availabilityIds, $sendingToQueue = true)
    {
        $this->getFactory()->createAvailabilityStorage($sendingToQueue)->publish($availabilityIds);
    }

    /**
     * @param array $availabilityIds
     * @param bool $sendingToQueue
     *
     * @return void
     */
    public function unpublish(array $availabilityIds, $sendingToQueue = true)
    {
        $this->getFactory()->createAvailabilityStorage($sendingToQueue)->unpublish($availabilityIds);
    }

}
