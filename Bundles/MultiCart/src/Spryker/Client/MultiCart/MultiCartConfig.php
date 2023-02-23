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
     * @return array<string>
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

    /**
     * Specification:
     * - Determines the `QuoteTransfer` fields that will be stored in storage.
     * - Supports nested fields filtering, e.g. [QuoteTransfer::ITEM => [ItemTransfer::GROUP_KEY]].
     * - Nesting level is not limited.
     *
     * @api
     *
     * @return array<string|array<string>>
     */
    public function getQuoteFieldsAllowedForCustomerQuoteCollectionInSession(): array
    {
        return [];
    }
}
