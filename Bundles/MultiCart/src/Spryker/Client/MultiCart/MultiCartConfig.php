<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MultiCart\MultiCartConfig getSharedConfig()
 */
class MultiCartConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getCustomerQuoteDefaultName(): string
    {
        return $this->getSharedConfig()->getCustomerQuoteDefaultName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGuestQuoteDefaultName(): string
    {
        return $this->getSharedConfig()->getGuestQuoteDefaultName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDuplicatedQuoteName(): string
    {
        return $this->getSharedConfig()->getDuplicatedQuoteName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getReorderQuoteName(): string
    {
        return $this->getSharedConfig()->getReorderQuoteName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQuickOrderQuoteName(): string
    {
        return $this->getSharedConfig()->getQuickOrderQuoteName();
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForQuoteDuplicate(): array
    {
        return [
            QuoteTransfer::ITEMS,
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
        ];
    }
}
