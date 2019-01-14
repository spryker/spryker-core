<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval\Plugin\Permission;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

class PlaceOrderPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'PlaceOrderPermissionPlugin';

    protected const FIELD_MULTI_CURRENCY = 'multi_currency';
    protected const KEY_SEPARATOR = '_';

    /**
     * {@inheritdoc}
     * - Checks if customer is allowed to place order with cent amount up to some value for specific currency, provided in configuration.
     * - Returns false, if quote is not provided.
     * - Returns true, if configuration does not have cent amount for specific currency set.
     *
     * @api
     *
     * @param array $configuration
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    public function can(array $configuration, $quoteTransfer = null): bool
    {
        if ($quoteTransfer === null || !($quoteTransfer instanceof QuoteTransfer)) {
            return false;
        }

        $centAmount = $quoteTransfer->getTotals()->getGrandTotal();
        $currencyCode = $quoteTransfer->getCurrency()->getCode();
        $storeName = $quoteTransfer->getStore()->getName();
        $key = $storeName . static::KEY_SEPARATOR . $currencyCode;

        if (!isset($configuration[static::FIELD_MULTI_CURRENCY][$key])) {
            return true;
        }

        if ($configuration[static::FIELD_MULTI_CURRENCY][$key] < (int)$centAmount) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getConfigurationSignature(): array
    {
        return [
            static::FIELD_MULTI_CURRENCY => static::CONFIG_FIELD_TYPE_MULTI_CURRENCY,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
