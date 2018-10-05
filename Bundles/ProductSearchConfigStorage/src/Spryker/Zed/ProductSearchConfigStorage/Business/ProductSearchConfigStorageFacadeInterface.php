<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Business;

interface ProductSearchConfigStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries search configs
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function publish();

    /**
     * Specification:
     * - Finds and deletes search configs storage
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function unpublish();
}
