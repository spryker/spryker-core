<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor;

use ArrayObject;

interface ProductOfferShipmentTypeExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return list<int>
     */
    public function extractProductOfferIdsFromProductOfferShipmentTypeTransfers(ArrayObject $productOfferShipmentTypeTransfers): array;
}
