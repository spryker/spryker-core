<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Quote\QuoteConfig getSharedConfig()
 */
class QuoteConfig extends AbstractBundleConfig
{
    protected const DEFAULT_GUEST_QUOTE_LIFETIME = 'P01M';

    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return $this->getSharedConfig()->getStorageStrategy();
    }

    /**
     * @return array
     */
    public function getQuoteFieldsAllowedForSaving()
    {
        return [
            QuoteTransfer::ITEMS,
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
        ];
    }

    /**
     * @return array
     */
    public function getQuoteFieldsTreeAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ITEMS => [
                ItemTransfer::ID,
                ItemTransfer::SKU,
                ItemTransfer::QUANTITY,
                ItemTransfer::ID_PRODUCT_ABSTRACT,
                ItemTransfer::IMAGES,
                ItemTransfer::NAME,
                ItemTransfer::UNIT_PRICE,
                ItemTransfer::SUM_PRICE,
                ItemTransfer::UNIT_GROSS_PRICE,
                ItemTransfer::SUM_GROSS_PRICE,
                ItemTransfer::IS_ORDERED,
            ],
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
        ];
    }

    /**
     * @return string
     */
    public function getGuestQuoteLifetime(): string
    {
        return $this->get(QuoteConstants::GUEST_QUOTE_LIFETIME, static::DEFAULT_GUEST_QUOTE_LIFETIME);
    }
}
