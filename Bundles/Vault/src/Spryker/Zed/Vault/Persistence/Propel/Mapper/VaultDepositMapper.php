<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\VaultDepositTransfer;
use Orm\Zed\Vault\Persistence\SpyVaultDeposit;

class VaultDepositMapper
{
    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     * @param \Orm\Zed\Vault\Persistence\SpyVaultDeposit $vaultDepositEntity
     *
     * @return \Orm\Zed\Vault\Persistence\SpyVaultDeposit
     */
    public function mapVaultDepositTransferToEntity(
        VaultDepositTransfer $vaultDepositTransfer,
        SpyVaultDeposit $vaultDepositEntity
    ): SpyVaultDeposit {
        $vaultDepositEntity->fromArray($vaultDepositTransfer->toArray());

        return $vaultDepositEntity;
    }

    /**
     * @param \Orm\Zed\Vault\Persistence\SpyVaultDeposit $vaultDepositEntity
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return \Generated\Shared\Transfer\VaultDepositTransfer
     */
    public function mapVaultEntityToTransfer(
        SpyVaultDeposit $vaultDepositEntity,
        VaultDepositTransfer $vaultDepositTransfer
    ): VaultDepositTransfer {
        $vaultDepositTransfer->fromArray($vaultDepositEntity->toArray(), true);

        return $vaultDepositTransfer;
    }
}
