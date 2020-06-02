<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote;

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
     * @api
     *
     * @return string
     */
    public function getStorageStrategy()
    {
        return $this->getSharedConfig()->getStorageStrategy();
    }

    /**
     * @api
     *
     * @return string[]
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
     * Specification:
     * - Returns item properties that should be stored in the quote table.
     * - Leave an empty array if you want to store all the Item transfer properties.
     *
     * @api
     *
     * @return string[]
     */
    public function getQuoteItemFieldsAllowedForSaving(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGuestQuoteLifetime(): string
    {
        return $this->get(QuoteConstants::GUEST_QUOTE_LIFETIME, static::DEFAULT_GUEST_QUOTE_LIFETIME);
    }
}
