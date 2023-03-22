<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     *
     * @var string
     */
    public const OPERATION_REMOVE = 'remove';

    /**
     * @var bool
     */
    protected const IS_ZERO_PRICE_ENABLED_FOR_CART_ACTIONS = true;

    /**
     * @api
     *
     * @return array<string>
     */
    public function getItemFieldsForIsSameItemComparison()
    {
        return [
            ItemTransfer::SKU,
        ];
    }

    /**
     * Specification:
     * - Returns the configuration value if zero prices are enabled for cart actions.
     * - The value should be set to `false` to avoid adding to cart items with zero price.
     *
     * @api
     *
     * @return bool
     */
    public function isZeroPriceEnabledForCartActions(): bool
    {
        return static::IS_ZERO_PRICE_ENABLED_FOR_CART_ACTIONS;
    }

    /**
     * Specification:
     * - Returns the list of fields that are used to build item's identifier.
     *
     * @api
     *
     * @return list<string>
     */
    public function getItemFieldsForIdentifier(): array
    {
        return [];
    }
}
