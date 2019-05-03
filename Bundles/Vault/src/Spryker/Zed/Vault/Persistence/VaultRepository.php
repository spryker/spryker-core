<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultTransfer;
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
     * @return \Generated\Shared\Transfer\VaultTransfer|null
     */
    public function findVaultByDataTypeAndKey(string $dataType, string $dataKey): ?VaultTransfer
    {
        $vaultEntity = $this->getFactory()
            ->createVaultPropelQuery()
            ->filterByDataKey($dataKey)
            ->filterByDataType($dataType)
            ->find()
            ->getFirst();

        if ($vaultEntity === null) {
            return null;
        }

        $vaultTransfer = $this->getFactory()
            ->createVaultMapper()
            ->mapVaultEntityToTransfer($vaultEntity, new VaultTransfer());

        return $vaultTransfer;
    }
}
