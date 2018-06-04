<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Business;

interface NavigationStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all navigation with the given navigationIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return void
     */
    public function publish(array $navigationIds);

    /**
     * Specification:
     * - Finds and deletes navigation storage entities with the given navigationIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return void
     */
    public function unpublish(array $navigationIds);
}
