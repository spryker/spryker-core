<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\Storage;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeStorageReaderInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeTransfer;
}
