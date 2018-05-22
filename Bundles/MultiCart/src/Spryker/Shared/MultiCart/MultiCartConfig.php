<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiCart;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class MultiCartConfig extends AbstractSharedConfig
{
    public const QUOTE_NAME_DEFAULT_GUEST = 'Guest shopping cart';
    public const QUOTE_NAME_DEFAULT_CUSTOMER = 'Shopping cart';
    public const QUOTE_NAME_DUPLICATE = '%s Copied At %s';
    public const QUOTE_NAME_REORDER = 'Cart from order %s';
    public const QUOTE_NAME_QUICK_ORDER = 'Quick order %s';

    /**
     * @return string
     */
    public function getGuestQuoteDefaultName(): string
    {
        return static::QUOTE_NAME_DEFAULT_GUEST;
    }

    /**
     * @return string
     */
    public function getCustomerQuoteDefaultName(): string
    {
        return static::QUOTE_NAME_DEFAULT_CUSTOMER;
    }

    /**
     * @return string
     */
    public function getDuplicatedQuoteName(): string
    {
        return static::QUOTE_NAME_DUPLICATE;
    }

    /**
     * @return string
     */
    public function getReorderQuoteName(): string
    {
        return static::QUOTE_NAME_REORDER;
    }

    /**
     * @return string
     */
    public function getQuickOrderQuoteName(): string
    {
        return static::QUOTE_NAME_QUICK_ORDER;
    }
}
