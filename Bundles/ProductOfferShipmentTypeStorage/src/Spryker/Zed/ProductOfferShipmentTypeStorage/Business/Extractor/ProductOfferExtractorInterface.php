<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor;

use ArrayObject;

interface ProductOfferExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<string>
     */
    public function extractProductOfferReferencesFromProductOfferTransfers(ArrayObject $productOfferTransfers): array;
}
