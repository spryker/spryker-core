<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Business;

use Spryker\Zed\IncrementalInstaller\Business\Creator\IncrementalInstallerCreator;
use Spryker\Zed\IncrementalInstaller\Business\Creator\IncrementalInstallerCreatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 * @method \Spryker\Zed\IncrementalInstaller\IncrementalInstallerConfig getConfig()
 */
class IncrementalInstallerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\IncrementalInstaller\Business\Creator\IncrementalInstallerCreatorInterface
     */
    public function createIncrementalInstallerCreator(): IncrementalInstallerCreatorInterface
    {
        return new IncrementalInstallerCreator($this->getEntityManager());
    }
}
