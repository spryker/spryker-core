<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business;

interface TaxProductStorageFacadeInterface
{
    /**
     * Specification:
     *  - Queries product tax sets for the given productAbstractIds.
     *  - Publishes product tax sets to storage.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void;

    /**
     * Specification:
     *  - Finds and removes product tax set storage entities for the given productAbstractIds.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void;
}
