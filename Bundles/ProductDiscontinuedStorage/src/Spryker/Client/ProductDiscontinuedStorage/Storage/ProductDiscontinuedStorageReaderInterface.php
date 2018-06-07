<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Storage;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface ProductDiscontinuedStorageReaderInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku): ?ProductDiscontinuedTransfer;
}
