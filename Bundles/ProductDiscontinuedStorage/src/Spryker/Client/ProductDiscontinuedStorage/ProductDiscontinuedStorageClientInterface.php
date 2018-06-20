<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
interface ProductDiscontinuedStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product discontinued within Storage with a given concrete product sku for given locale.
     * - Returns null if product discontinued was not found.
     *
     * @api
     *
     * @param string $concreteSku
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer;
}
