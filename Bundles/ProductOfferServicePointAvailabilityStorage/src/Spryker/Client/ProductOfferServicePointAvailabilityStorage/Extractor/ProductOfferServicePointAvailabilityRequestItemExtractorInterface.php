<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor;

use ArrayObject;

interface ProductOfferServicePointAvailabilityRequestItemExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers
     *
     * @return list<string>
     */
    public function extractProductConcreteSkusFromProductOfferServicePointAvailabilityRequestItems(
        ArrayObject $productOfferServicePointAvailabilityRequestItemTransfers
    ): array;
}
