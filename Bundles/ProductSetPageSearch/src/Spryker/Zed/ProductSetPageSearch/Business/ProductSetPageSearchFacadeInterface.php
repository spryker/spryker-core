<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

interface ProductSetPageSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all productSet with productSetIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds);

    /**
     * Specification:
     * - Finds and deletes productSet storage entities with productSetIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds);
}
