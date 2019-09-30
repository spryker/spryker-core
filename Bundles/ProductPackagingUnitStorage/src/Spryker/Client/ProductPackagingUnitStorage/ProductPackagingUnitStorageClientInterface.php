<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;

interface ProductPackagingUnitStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves a product concrete packaging information for the provided product concrete ID.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductConcrete): ?ProductConcretePackagingStorageTransfer;

    /**
     * Specification:
     * - Expands ItemTransfer with packaging unit data if available.
     * - Uses the default amount and default measurement unit settings.
     * - Returns ItemTransfer unchanged if no packaging unit data is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer;
}
