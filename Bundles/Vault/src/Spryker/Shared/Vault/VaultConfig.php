<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Vault;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Vault\Exception\EncryptionKeyNotPreConfigured;

class VaultConfig extends AbstractSharedConfig
{
    /**
     * @throws \Spryker\Shared\Vault\Exception\EncryptionKeyNotPreConfigured
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        $encryptionKey = $this->get(VaultConstants::ENCRYPTION_KEY, false);

        if ($encryptionKey) {
            return $encryptionKey;
        }

        throw new EncryptionKeyNotPreConfigured("Encryption key is not pre-configured, please update VAULT:ENCRYPTION_KEY env variable.");
    }
}
