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
    /**
     * @param string $dataType
     *
     * @return string
     */
    public function getEncryptionKeyPerType(string $dataType): string
    {
        return $this->getSharedConfig()->getEncriptinoKeyPerType($dataType);
    }
}
