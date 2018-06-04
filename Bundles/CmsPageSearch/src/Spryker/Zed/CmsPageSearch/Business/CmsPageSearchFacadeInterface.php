<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business;

interface CmsPageSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all cms pages with cmsPageIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds);

    /**
     * Specification:
     * - Finds and deletes cms pages storage entities with cmsPageIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds);
}
