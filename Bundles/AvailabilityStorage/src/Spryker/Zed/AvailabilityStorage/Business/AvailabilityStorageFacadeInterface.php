<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business;

interface AvailabilityStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all availabilities with these ids
     * - Creates a data structure tree
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds);

    /**
     * Specification:
     * - Finds and deletes availability storage entities based on these ids
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds);

    /**
     * Specification:
     * - Queries all availability storage entities with provided product abstract ids.
     * - Stores data as JSON encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishByProductAbstractIds(array $productAbstractIds): void;

    /**
     * Specification:
     * - Finds and deletes availability storage entities based on provided product abstract ids.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishByProductAbstractIds(array $productAbstractIds): void;
}
