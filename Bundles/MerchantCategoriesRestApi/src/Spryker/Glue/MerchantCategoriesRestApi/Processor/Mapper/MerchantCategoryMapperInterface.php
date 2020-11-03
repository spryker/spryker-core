<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

interface MerchantCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryStorageTransfer[] $merchantCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapCategoryTransfersToRestMerchantsAttributesTransfer(
        array $merchantCategoryStorageTransfers,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer;
}
