<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Installer\Business\Model\GlossaryInstaller;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Installer\InstallerConfig;
use Spryker\Zed\Installer\InstallerDependencyProvider;

/**
 * @method InstallerConfig getConfig()
 */
class InstallerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstallers()
    {
        return $this->getProvidedDependency(InstallerDependencyProvider::INSTALLERS);
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstallers()
    {
        return $this->getProvidedDependency(InstallerDependencyProvider::INSTALLERS_DEMO_DATA);
    }

    /**
     * @return GlossaryInstaller
     */
    public function getGlossaryInstaller()
    {
        return new GlossaryInstaller(
            $this->getProvidedDependency(InstallerDependencyProvider::FACADE_GLOSSARY),
            $this->getConfig()->getGlossaryFilePaths()
        );
    }

}
