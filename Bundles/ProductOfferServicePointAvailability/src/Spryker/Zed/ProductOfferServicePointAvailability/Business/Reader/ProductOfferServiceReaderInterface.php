<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;

interface ProductOfferServiceReaderInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollectionByProductOfferIds(array $productOfferIds): ProductOfferServiceCollectionTransfer;
}
