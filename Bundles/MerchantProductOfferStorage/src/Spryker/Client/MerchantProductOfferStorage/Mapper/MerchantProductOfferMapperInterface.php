<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface MerchantProductOfferMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function mapMerchantProductOfferStorageDataToProductOfferStorageTransfer(
        array $data,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer;
}
