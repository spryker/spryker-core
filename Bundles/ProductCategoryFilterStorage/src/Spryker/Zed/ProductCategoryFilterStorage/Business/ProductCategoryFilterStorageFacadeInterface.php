<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Business;

interface ProductCategoryFilterStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all productCategoryFilter with the given categoryIds
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
     * - Finds and deletes productCategoryFilter entities with categoryIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds);
}
