<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Mapper;

use Generated\Shared\Transfer\ProductOfferViewTransfer;

interface MerchantProductOfferMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\ProductOfferViewTransfer $productOfferViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewTransfer
     */
    public function mapMerchantProductOfferStorageDataToProductOfferViewTransfer(array $data, ProductOfferViewTransfer $productOfferViewTransfer): ProductOfferViewTransfer;
}
