<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\PermissionChecker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class QuotePermissionChecker implements QuotePermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CartsRestApi\CartsRestApiConfig
     */
    protected $config;

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin::KEY
     */
    protected const PERMISSION_PLUGIN_KEY_READ_SHARED_CART = 'ReadSharedCartPermissionPlugin';

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin::KEY
     */
    protected const PERMISSION_PLUGIN_KEY_WRITE_SHARED_CART = 'WriteSharedCartPermissionPlugin';

    /**
     * @param \Spryker\Zed\CartsRestApi\CartsRestApiConfig $config
     */
    public function __construct(CartsRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteReadPermission(QuoteTransfer $quoteTransfer): bool
    {
        return $this->checkQuotePermission($quoteTransfer, static::PERMISSION_PLUGIN_KEY_READ_SHARED_CART);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteWritePermission(QuoteTransfer $quoteTransfer): bool
    {
        return $this->checkQuotePermission($quoteTransfer, static::PERMISSION_PLUGIN_KEY_WRITE_SHARED_CART);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function checkQuotePermission(QuoteTransfer $quoteTransfer, string $permissionPluginKey): bool
    {
        $quoteTransfer->requireIdQuote();

        $customerReference = $quoteTransfer->getCustomerReference();
        if (strpos($customerReference, $this->config->getAnonymousPrefix()) !== false) {
            return true;
        }

        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()
            || !$quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()) {
            return true;
        }

        return $this->can(
            $permissionPluginKey,
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(),
            $quoteTransfer->getIdQuote()
        );
    }
}
