<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultDepositTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Vault\Persistence\VaultPersistenceFactory getFactory()
 */
class VaultRepository extends AbstractRepository implements VaultRepositoryInterface
{
    /**
     * @param string $dataType
     * @param string $dataKey
     *
     * @return \Generated\Shared\Transfer\VaultDepositTransfer|null
     */
    public function findVaultDepositByDataTypeAndKey(string $dataType, string $dataKey): ?VaultDepositTransfer
    {
        $vaultDepositEntity = $this->getFactory()
            ->createVaultDepositPropelQuery()
            ->filterByDataKey($dataKey)
            ->filterByDataType($dataType)
            ->findOne();

        if ($vaultDepositEntity === null) {
            return null;
        }

        $vaultDepositTransfer = $this->getFactory()
            ->createVaultDepositMapper()
            ->mapVaultEntityToTransfer($vaultDepositEntity, new VaultDepositTransfer());

        return $vaultDepositTransfer;
    }
}
