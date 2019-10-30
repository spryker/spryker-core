<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Storage;

use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;

interface ProductPackagingUnitStorageReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null
     */
    public function findProductPackagingUnitById(int $idProductConcrete): ?ProductPackagingUnitStorageTransfer;
}
