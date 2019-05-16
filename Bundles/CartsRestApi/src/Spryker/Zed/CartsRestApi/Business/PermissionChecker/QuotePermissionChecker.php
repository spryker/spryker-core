<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\PermissionChecker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class QuotePermissionChecker
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin::KEY
     */
    protected const READ_SHARED_CART_PERMISSION_PLUGIN_KEY = 'ReadSharedCartPermissionPlugin';

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin::KEY
     */
    protected const WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY = 'WriteSharedCartPermissionPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteReadPermission(QuoteTransfer $quoteTransfer): bool
    {
        return $this->checkQuotePermission($quoteTransfer, static::READ_SHARED_CART_PERMISSION_PLUGIN_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteWritePermission(QuoteTransfer $quoteTransfer): bool
    {
        return $this->checkQuotePermission($quoteTransfer, static::WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function checkQuotePermission(QuoteTransfer $quoteTransfer, $permissionPluginKey): bool
    {
        $quoteTransfer->requireIdQuote();

        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()) {
            return true;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()) {
            return true;
        }

        return $this->can(
            $permissionPluginKey,
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(),
            $quoteTransfer->getIdQuote()
        );
    }
}
