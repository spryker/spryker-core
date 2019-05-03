<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Vault;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Vault\Exception\EncryptionKeyNotPreConfiguredForDataType;

class VaultConfig extends AbstractSharedConfig
{
    /**
     * @param string $dataType
     *
     * @throws \Spryker\Shared\Vault\Exception\EncryptionKeyNotPreConfiguredForDataType
     *
     * @return string
     */
    public function getEncriptinoKeyPerType(string $dataType): string
    {
        $encryptionKeysPerType = $this->get(VaultConstants::ENCRYPTION_KEYS_PER_TYPE, []);

        if (isset($encryptionKeysPerType[$dataType])) {
            return $encryptionKeysPerType[$dataType];
        }

        throw new EncryptionKeyNotPreConfiguredForDataType(
            sprintf("Encryption key is not pre-configured for \"%s\" data type, please update ENCRYPTION_KEYS_PER_TYPE env variable.", $dataType)
        );
    }
}
