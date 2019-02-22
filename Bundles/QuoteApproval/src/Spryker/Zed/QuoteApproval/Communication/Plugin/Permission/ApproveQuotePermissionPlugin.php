<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

class ApproveQuotePermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'ApproveQuotePermissionPlugin';
    public const FIELD_STORE_MULTI_CURRENCY = 'store_multi_currency';

    /**
     * {@inheritdoc}
     * - Checks if approver is allowed to approve order with cent amount up to some value for specific currency, provided in configuration.
     * - Returns false, if context is not provided.
     * - Returns true, if configuration does not have cent amount for specific currency set.
     *
     * @api
     *
     * @param array $configuration
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can(array $configuration, $context = null): bool
    {
        if ($context === null) {
            return false;
        }

        $centAmount = $context[QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT];
        $storeName = $context[QuoteApprovalConfig::PERMISSION_CONTEXT_STORE_NAME];
        $currencyCode = $context[QuoteApprovalConfig::PERMISSION_CONTEXT_CURRENCY_CODE];

        if (!isset($configuration[static::FIELD_STORE_MULTI_CURRENCY][$storeName][$currencyCode])) {
            return true;
        }

        if ($configuration[static::FIELD_STORE_MULTI_CURRENCY][$storeName][$currencyCode] < $centAmount) {
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
            static::FIELD_STORE_MULTI_CURRENCY => static::CONFIG_FIELD_TYPE_STORE_MULTI_CURRENCY,
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
