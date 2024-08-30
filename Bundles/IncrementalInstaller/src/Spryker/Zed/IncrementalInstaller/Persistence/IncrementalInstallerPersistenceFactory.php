<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Persistence;

use Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstallerQuery;
use Spryker\Zed\IncrementalInstaller\Persistence\Propel\Mapper\IncrementalInstallerMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 * @method \Spryker\Zed\IncrementalInstaller\IncrementalInstallerConfig getConfig()
 */
class IncrementalInstallerPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstallerQuery
     */
    public function createIncrementalInstallerPropelQuery(): SpyIncrementalInstallerQuery
    {
        return SpyIncrementalInstallerQuery::create();
    }

    /**
     * @return \Spryker\Zed\IncrementalInstaller\Persistence\Propel\Mapper\IncrementalInstallerMapper
     */
    public function createIncrementalInstallerMapper(): IncrementalInstallerMapper
    {
        return new IncrementalInstallerMapper();
    }
}
