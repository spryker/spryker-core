<?php


namespace Spryker\Zed\CustomerAccessStorage\Business;


interface CustomerAccessStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all customer access
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function publish();
}