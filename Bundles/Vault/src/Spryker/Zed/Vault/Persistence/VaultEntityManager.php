<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultDepositTransfer;
use Orm\Zed\Vault\Persistence\SpyVaultDeposit;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Vault\Persistence\VaultPersistenceFactory getFactory()
 */
class VaultEntityManager extends AbstractEntityManager implements VaultEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return bool
     */
    public function createVaultDeposit(VaultDepositTransfer $vaultDepositTransfer): bool
    {
        $vaultDepositEntity = $this->getFactory()
            ->createVaultDepositMapper()
            ->mapVaultDepositTransferToEntity(
                $vaultDepositTransfer,
                new SpyVaultDeposit()
            );

        return (bool)$vaultDepositEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return void
     */
    public function updateVaultDeposit(VaultDepositTransfer $vaultDepositTransfer): void
    {
        $vaultDepositEntity = $this->getFactory()
            ->createVaultDepositPropelQuery()
            ->filterByDataType($vaultDepositTransfer->getDataType())
            ->filterByDataKey($vaultDepositTransfer->getDataKey())
            ->findOne();

        $vaultDepositEntity = $this->getFactory()
            ->createVaultDepositMapper()
            ->mapVaultDepositTransferToEntity(
                $vaultDepositTransfer,
                $vaultDepositEntity
            );

        $vaultDepositEntity->save();
    }
}
