<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\CartEditStatus;

use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Kernel\PermissionAwareTrait;

class CartEditStatusChecker implements CartEditStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin::KEY
     */
    protected const PERMISSION_WRITE_SHARED_CART = 'WriteSharedCartPermissionPlugin';

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface $quoteClient
     */
    public function __construct(CartToQuoteInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return bool
     */
    public function isCartEditable(): bool
    {
        return !$this->quoteClient->isQuoteLocked() && $this->hasWritePermission();
    }

    /**
     * @return bool
     */
    protected function hasWritePermission(): bool
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        return $this->can(static::PERMISSION_WRITE_SHARED_CART, $quoteTransfer->getIdQuote());
    }
}
