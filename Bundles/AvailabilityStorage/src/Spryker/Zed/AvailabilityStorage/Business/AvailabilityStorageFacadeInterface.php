<?php

namespace Spryker\Zed\AvailabilityStorage\Business;

interface AvailabilityStorageFacadeInterface
{

    /**
     * @param array $availabilityIds
     * @param bool $sendingToQueue
     *
     * @return void
     */
    public function publish(array $availabilityIds, $sendingToQueue = true);

    /**
     * @param array $availabilityIds
     *
     * @param bool $sendingToQueue
     *
     * @return void
     */
    public function unpublish(array $availabilityIds, $sendingToQueue = true);
}
