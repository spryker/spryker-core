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
    protected const GUEST_CART_LIFETIME_DEFAULT = 'P01M';

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
     * @return string
     */
    public function getGuestCartLifetime(): string
    {
        return $this->get(QuoteConstants::GUEST_CART_LIFETIME, static::GUEST_CART_LIFETIME_DEFAULT);
    }
}
