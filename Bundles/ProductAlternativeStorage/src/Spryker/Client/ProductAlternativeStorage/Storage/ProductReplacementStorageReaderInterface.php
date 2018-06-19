<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\Storage;

use Generated\Shared\Transfer\ProductReplacementStorageTransfer;

interface ProductReplacementStorageReaderInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $sku): ?ProductReplacementStorageTransfer;
}
