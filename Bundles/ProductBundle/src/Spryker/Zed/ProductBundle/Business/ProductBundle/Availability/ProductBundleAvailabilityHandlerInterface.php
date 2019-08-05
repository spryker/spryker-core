<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Generated\Shared\Transfer\StoreTransfer;

interface ProductBundleAvailabilityHandlerInterface
{
    /**
     * @param string $bundledProductSku
     *
     * @return void
     */
    public function updateAffectedBundlesAvailability($bundledProductSku);

    /**
     * @param string $bundleProductSku
     *
     * @return void
     */
    public function updateBundleAvailability($bundleProductSku);

    /**
     * @param string $bundleProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function removeBundleAvailability($bundleProductSku, StoreTransfer $storeTransfer);
}
