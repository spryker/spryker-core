<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business;

interface ContentStorageFacadeInterface
{
    /**
     * Specification:
     * - Fetches content by IDs.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array $contentIds
     *
     * @return void
     */
    public function publish(array $contentIds): void;
}
