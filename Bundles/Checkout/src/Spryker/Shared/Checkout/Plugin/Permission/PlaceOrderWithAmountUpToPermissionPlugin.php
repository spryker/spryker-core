<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Checkout\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

/**
 * For Zed & Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class PlaceOrderWithAmountUpToPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'PlaceOrderWithAmountUpToPermissionPlugin';

    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * {@inheritDoc}
     * - Checks if customer is allowed to place order with cent amount up to some value, provided in configuration.
     * - Returns false, if customer cent amount is not provided.
     * - Returns true, if configuration does not have cent amount set.
     *
     * @param array $configuration
     * @param int|null $centAmount
     *
     * @return bool
     */
    public function can(array $configuration, $centAmount = null): bool
    {
        if ($centAmount === null) {
            return false;
        }

        if (!isset($configuration[static::FIELD_CENT_AMOUNT])) {
            return true;
        }

        if ($configuration[static::FIELD_CENT_AMOUNT] <= (int)$centAmount) {
            return false;
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function getConfigurationSignature(): array
    {
        return [
            static::FIELD_CENT_AMOUNT => static::CONFIG_FIELD_TYPE_INT,
        ];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
