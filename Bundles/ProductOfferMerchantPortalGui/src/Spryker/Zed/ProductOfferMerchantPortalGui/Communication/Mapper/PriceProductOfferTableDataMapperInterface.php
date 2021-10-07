<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer;

interface PriceProductOfferTableDataMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    public function mapPriceProductTransfersToPriceProductOfferTableViewCollectionTransfer(
        array $priceProductTransfers,
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
    ): PriceProductOfferTableViewCollectionTransfer;
}
