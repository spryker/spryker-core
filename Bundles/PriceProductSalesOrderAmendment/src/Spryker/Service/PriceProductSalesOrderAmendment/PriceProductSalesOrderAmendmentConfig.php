<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractBundleConfig;

class PriceProductSalesOrderAmendmentConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines if best prices between original product/offer price and sales order item price should be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    public function useBestPriceBetweenOriginalAndSalesOrderItemPrice(?QuoteTransfer $quoteTransfer = null): bool
    {
        return true;
    }
}
