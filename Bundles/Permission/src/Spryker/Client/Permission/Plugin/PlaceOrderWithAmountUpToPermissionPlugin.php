<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class PlaceOrderWithAmountUpToPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'PlaceOrderWithAmountUpToPermissionPlugin';

    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * @param array $configuration
     * @param int|null $centAmount
     *
     * @return bool
     */
    public function can(array $configuration, $centAmount = null): bool
    {
        if (!$centAmount) {
            return false;
        }

        if (!isset($configuration[static::FIELD_CENT_AMOUNT])) {
            return false;
        }

        if ((int)$configuration[static::FIELD_CENT_AMOUNT] <= (int)$centAmount) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getConfigurationSignature(): array
    {
        return [
            static::FIELD_CENT_AMOUNT => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT,
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
