<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Vault\VaultConfig getSharedConfig()
 */
class VaultConfig extends AbstractBundleConfig
{
    protected const USE_BYTE_STRING_FOR_ENCRYPTION_INITIALIZATION_VECTOR = false;

    /**
     * @api
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->getSharedConfig()->getEncryptionKey();
    }

    /**
     * Specification:
     * - If true, initial vector will be converted to binary format for Vault operations.
     * - If false, hexadecimal value will be used.
     *
     * @api
     *
     * @return bool
     */
    public function useByteStringForEncryptionInitializationVector(): bool
    {
        return static::USE_BYTE_STRING_FOR_ENCRYPTION_INITIALIZATION_VECTOR;
    }
}
