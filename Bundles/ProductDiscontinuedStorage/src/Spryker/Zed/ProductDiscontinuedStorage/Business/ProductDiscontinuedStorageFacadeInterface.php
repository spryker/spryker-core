<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business;

interface ProductDiscontinuedStorageFacadeInterface
{
    /**
     * Specification:
     *  - Publishes product discontinued changes to storage.
     *
     * @api
     *
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void;

    /**
     * Specification:
     *  - Remove product discontinued from storage.
     *
     * @api
     *
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function unpublish(array $productDiscontinuedIds): void;
}
