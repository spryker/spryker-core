<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Communication;

use Spryker\Zed\IncrementalInstaller\IncrementalInstallerDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerFacadeInterface getFacade()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 * @method \Spryker\Zed\IncrementalInstaller\IncrementalInstallerConfig getConfig()
 */
class IncrementalInstallerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface>
     */
    public function getIncrementalInstallerPlugins(): array
    {
        return $this->getProvidedDependency(IncrementalInstallerDependencyProvider::PLUGINS_INCREMENTAL_INSTALLER);
    }
}
