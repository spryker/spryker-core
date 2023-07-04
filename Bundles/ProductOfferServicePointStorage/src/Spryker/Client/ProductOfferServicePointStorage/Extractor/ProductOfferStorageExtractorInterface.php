<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Extractor;

use ArrayObject;

interface ProductOfferStorageExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return list<string>
     */
    public function extractProductOfferReferencesFromProductOfferStorageTransfers(ArrayObject $productOfferStorageTransfers): array;
}
