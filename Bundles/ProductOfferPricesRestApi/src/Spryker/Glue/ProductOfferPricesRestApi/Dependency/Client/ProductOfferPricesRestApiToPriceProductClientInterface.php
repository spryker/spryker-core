<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface ProductOfferPricesRestApiToPriceProductClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransferByPriceProductFilter(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): CurrentProductPriceTransfer;
}
