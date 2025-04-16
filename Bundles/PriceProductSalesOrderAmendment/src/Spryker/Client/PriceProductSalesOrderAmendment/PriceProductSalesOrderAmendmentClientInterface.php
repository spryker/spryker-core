<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductSalesOrderAmendmentClientInterface
{
    /**
     * Specification:
     * - Requires `PriceProductTransfer.moneyValue` to be set.
     * - Expects `PriceProductFilterTransfer.quote` to be set.
     * - Expects `PriceProductFilterTransfer.quote.amendmentOrderReference` to be set.
     * - Expects `PriceProductFilterTransfer.priceProductResolveConditions` to be set.
     * - Resolves the price for order-amendment items.
     * - For found prices uses {@link \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig::useBestPriceBetweenOriginalAndSalesOrderItemPrice()} to determine if best price should be used.
     * - If the config method returns `true` replaces price in case the original price is lower then the original price.
     * - Configuration applies for all items. It is not possible to set it for each item separately.
     * - Otherwise replaces the default price anyway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function resolveOrderAmendmentPrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductTransfer;
}
