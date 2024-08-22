<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class QuoteCheckoutConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const STORAGE_QUOTE_CHECKOUT_LOCK_NAMESPACE = 'quote:checkout:lock';

    /**
     * @var int
     */
    protected const DEFAULT_TTL_QUOTE_CHECKOUT_LOCK = 3;

    /**
     * Specification:
     * - Returns the time to live for a lock that prevents a quote from checkout.
     *
     * @api
     *
     * @return int
     */
    public function getTtlQuoteCheckoutLock(): int
    {
        return static::DEFAULT_TTL_QUOTE_CHECKOUT_LOCK;
    }

    /**
     * Specification:
     * - Returns the namespace for the storage lock keys.
     *
     * @api
     *
     * @return string
     */
    public function getQuoteCheckoutLockStorageNamespace(): string
    {
        return static::STORAGE_QUOTE_CHECKOUT_LOCK_NAMESPACE;
    }
}
