<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Mapper;

use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;

interface ProductOfferServiceStorageMapperInterface
{
    /**
     * @param array<string, mixed> $productOfferServiceStorageData
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer
     */
    public function mapProductOfferServiceStorageDataToProductOfferServiceStorageTransfer(
        array $productOfferServiceStorageData,
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
    ): ProductOfferServiceStorageTransfer;
}
