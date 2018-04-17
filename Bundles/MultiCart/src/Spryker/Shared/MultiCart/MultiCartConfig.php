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
    public const QUOTE_NAME_SUFFIX_DUPLICATE = ' Copied At';

    /**
     * @return string
     */
    public function getGuestQuoteDefaultName()
    {
        return static::QUOTE_NAME_DEFAULT_GUEST;
    }

    /**
     * @return string
     */
    public function getCustomerQuoteDefaultName()
    {
        return static::QUOTE_NAME_DEFAULT_CUSTOMER;
    }

    /**
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix()
    {
        return static::QUOTE_NAME_SUFFIX_DUPLICATE;
    }
}
