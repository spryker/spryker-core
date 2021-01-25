<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Business;

interface CmsBlockStorageFacadeInterface
{
    /**
     * Specification:
     * - Aggregates all cms block related data for given cms block IDs
     * - Saves aggregated data to database
     * - Sends aggregated data to synchronization queue
     *
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function publish(array $cmsBlockIds): void;

    /**
     * Specification:
     * - Delete cms stored block data for given cms block IDs
     * - Sends deleted keys to synchronization queue
     *
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function unpublish(array $cmsBlockIds): void;
}
