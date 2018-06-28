<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business;

interface CmsBlockCategoryStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all cms block categories with categoryIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds);

    /**
     * Specification:
     * - Finds and deletes cms block category storage entities with categoryIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $categoryIds);
}
