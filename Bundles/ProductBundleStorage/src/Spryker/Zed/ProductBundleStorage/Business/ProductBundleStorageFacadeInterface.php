<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business;

interface ProductBundleStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes product_bundle data to storage based on product_bundle events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductConcreteBundleIdsEvents(array $eventTransfers): void;
}
