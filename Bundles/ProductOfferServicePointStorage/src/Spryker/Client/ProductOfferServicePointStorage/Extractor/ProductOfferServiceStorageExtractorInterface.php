<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Extractor;

use ArrayObject;

interface ProductOfferServiceStorageExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer> $productOfferServiceStorageTransfers
     *
     * @return list<string>
     */
    public function extractServicePointUuidsFromProductOfferServiceStorageTransfers(ArrayObject $productOfferServiceStorageTransfers): array;
}
