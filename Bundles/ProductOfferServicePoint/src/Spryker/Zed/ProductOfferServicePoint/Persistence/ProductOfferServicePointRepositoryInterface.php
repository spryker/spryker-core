<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;

interface ProductOfferServicePointRepositoryInterface
{
    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferServiceCollectionTransfer;
}
