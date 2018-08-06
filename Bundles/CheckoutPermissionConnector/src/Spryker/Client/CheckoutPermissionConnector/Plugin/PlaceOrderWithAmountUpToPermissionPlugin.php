<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutPermissionConnector\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class PlaceOrderWithAmountUpToPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'PlaceOrderWithAmountUpToPermissionPlugin';

    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * {@inheritdoc}
     * - Returns true if provided cent amount is lesser equal than the provided expected value.
     * - Returns false in case the cent amount or expected value is not provided.
     *
     * @param array $configuration
     * @param int|null $centAmount
     *
     * @return bool
     */
    public function can(array $configuration, $centAmount = null): bool
    {
        if (null === $centAmount) {
            return false;
        }

        if (!isset($configuration[static::FIELD_CENT_AMOUNT])) {
            return false;
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
