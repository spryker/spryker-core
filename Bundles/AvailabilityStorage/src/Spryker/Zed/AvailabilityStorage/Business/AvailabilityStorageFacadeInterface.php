<?php

namespace Spryker\Zed\AvailabilityStorage\Business;

interface AvailabilityStorageFacadeInterface
{

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds);

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds);
}
