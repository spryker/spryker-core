<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;

interface PriceProductTableDataMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function mapPriceProductTransfersToPriceProductTableViewCollectionTransfer(
        array $priceProductTransfers,
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
    ): PriceProductTableViewCollectionTransfer;
}
