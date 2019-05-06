<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\VaultTransfer;
use Orm\Zed\Vault\Persistence\SpyVault;

class VaultMapper
{
    /**
     * @param \Generated\Shared\Transfer\VaultTransfer $vaultTransfer
     * @param \Orm\Zed\Vault\Persistence\SpyVault $vaultEntity
     *
     * @return \Orm\Zed\Vault\Persistence\SpyVault
     */
    public function mapVaultTransferToEntity(
        VaultTransfer $vaultTransfer,
        SpyVault $vaultEntity
    ): SpyVault {
        $vaultEntity->fromArray($vaultTransfer->toArray());
        $vaultEntity->setCipherText(base64_encode($vaultTransfer->getCipherText()));

        return $vaultEntity;
    }

    /**
     * @param \Orm\Zed\Vault\Persistence\SpyVault $vaultEntity
     * @param \Generated\Shared\Transfer\VaultTransfer $vaultTransfer
     *
     * @return \Generated\Shared\Transfer\VaultTransfer
     */
    public function mapVaultEntityToTransfer(
        SpyVault $vaultEntity,
        VaultTransfer $vaultTransfer
    ): VaultTransfer {
        $vaultTransfer->fromArray($vaultEntity->toArray(), true);
        $vaultTransfer->setCipherText(base64_decode($vaultEntity->getCipherText()));

        return $vaultTransfer;
    }
}
