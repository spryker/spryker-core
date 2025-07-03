<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderAmendmentConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the format of the quote name for the cart reorder.
     *
     * @api
     *
     * @return string
     */
    public function getCartReorderQuoteNameFormat(): string
    {
        return 'Editing Order %s';
    }

    /**
     * Specification:
     * - Returns item properties that should be stored in the quote table.
     * - If it would be an empty array, quote will not be stored.
     *
     * @api
     *
     * @return array<string>
     */
    public function getQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ITEMS,
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE,
            QuoteTransfer::QUOTE_PROCESS_FLOW,
            QuoteTransfer::ORIGINAL_SALES_ORDER_ITEMS,
        ];
    }

    /**
     * Specification:
     * - Returns item properties that should be stored in the quote table.
     * - Leave an empty array if you want to store all the Item transfer properties.
     *
     * @api
     *
     * @return array<string>
     */
    public function getQuoteItemFieldsAllowedForSaving(): array
    {
        return [];
    }
}
