<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Orm\Zed\Vault\Persistence\SpyVaultQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Vault\Persistence\Propel\Mapper\VaultMapper;

/**
 * @method \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Vault\VaultConfig getConfig()
 * @method \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface getRepository()
 */
class VaultPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Vault\Persistence\SpyVaultQuery
     */
    public function createVaultPropelQuery(): SpyVaultQuery
    {
        return SpyVaultQuery::create();
    }

    /**
     * @return \Spryker\Zed\Vault\Persistence\Propel\Mapper\VaultMapper
     */
    public function createVaultMapper(): VaultMapper
    {
        return new VaultMapper();
    }
}
