<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Orm\Zed\Vault\Persistence\SpyVaultDepositQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Vault\Persistence\Propel\Mapper\VaultDepositMapper;

/**
 * @method \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Vault\VaultConfig getConfig()
 * @method \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface getRepository()
 */
class VaultPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Vault\Persistence\SpyVaultDepositQuery
     */
    public function createVaultDepositPropelQuery(): SpyVaultDepositQuery
    {
        return SpyVaultDepositQuery::create();
    }

    /**
     * @return \Spryker\Zed\Vault\Persistence\Propel\Mapper\VaultDepositMapper
     */
    public function createVaultDepositMapper(): VaultDepositMapper
    {
        return new VaultDepositMapper();
    }
}
