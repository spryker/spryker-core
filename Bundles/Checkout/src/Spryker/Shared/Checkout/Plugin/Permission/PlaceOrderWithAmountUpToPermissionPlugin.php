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
    /**
     * @var string
     */
    public const KEY = 'PlaceOrderWithAmountUpToPermissionPlugin';

    /**
     * @var string
     */
    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * {@inheritDoc}
     * - Checks if customer is allowed to place order with cent amount up to some value, provided in configuration.
     * - Returns false, if customer cent amount is not provided.
     * - Returns true, if configuration does not have cent amount set.
     *
     * @param array<string, mixed> $configuration
     * @param array|string|int|null $context Cent amount.
     *
     * @return bool
     */
    public function can(array $configuration, $context = null): bool
    {
        if ($context === null) {
            return false;
        }

        if (!isset($configuration[static::FIELD_CENT_AMOUNT])) {
            return true;
        }

        if (!is_array($context) && (int)$configuration[static::FIELD_CENT_AMOUNT] <= (int)$context) {
            return false;
        }

        return true;
    }

    /**
     * @return array<string>
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
