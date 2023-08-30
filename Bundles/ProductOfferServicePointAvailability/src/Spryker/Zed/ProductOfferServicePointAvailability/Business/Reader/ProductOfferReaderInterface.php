<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

interface ProductOfferReaderInterface
{
    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferCollectionTransfer;
}
