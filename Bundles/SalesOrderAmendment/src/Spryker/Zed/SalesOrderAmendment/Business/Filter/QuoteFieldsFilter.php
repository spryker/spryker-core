<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Filter;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig;

class QuoteFieldsFilter implements QuoteFieldsFilterInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig $salesOrderAmendmentConfig
     */
    public function __construct(protected SalesOrderAmendmentConfig $salesOrderAmendmentConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string|array<string>>
     */
    public function filterQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        $quoteFields = array_unique($this->salesOrderAmendmentConfig->getQuoteFieldsAllowedForSaving());
        $quoteItemFields = $this->salesOrderAmendmentConfig->getQuoteItemFieldsAllowedForSaving();

        if (!$quoteItemFields) {
            return $quoteFields;
        }

        $itemFieldsPosition = array_search(QuoteTransfer::ITEMS, $quoteFields, true);

        if ($itemFieldsPosition !== false) {
            $quoteFields[QuoteTransfer::ITEMS] = $quoteItemFields;
            unset($quoteFields[$itemFieldsPosition]);
        }

        return $quoteFields;
    }
}
