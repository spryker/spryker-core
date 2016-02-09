<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Installer\Business\Model\GlossaryInstaller;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Installer\InstallerDependencyProvider;
use Spryker\Zed\Installer\Business\Model\IcecatInstaller;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\InstallerConfig getConfig()
 */
class InstallerBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Installer\Business\Model\AbstractInstaller[]
     */
    public function getInstallers()
    {
        return $this->getProvidedDependency(InstallerDependencyProvider::INSTALLERS);
    }

    /**
     * @return \Spryker\Zed\Installer\Business\Model\AbstractInstaller[]
     */
    public function getDemoDataInstallers()
    {
        return $this->getProvidedDependency(InstallerDependencyProvider::INSTALLERS_DEMO_DATA);
    }

    /**
     * @return \Spryker\Zed\Installer\Business\Model\GlossaryInstaller
     */
    public function createGlossaryInstaller()
    {
        return new GlossaryInstaller(
            $this->getProvidedDependency(InstallerDependencyProvider::FACADE_GLOSSARY),
            $this->getConfig()->getGlossaryFilePaths()
        );
    }

    /**
     * @param OutputInterface $output
     *
     * @return \Spryker\Zed\Installer\Business\Model\IcecatInstaller
     */
    public function getIcecatDataInstaller(OutputInterface $output)
    {
        return new IcecatInstaller($output);
    }

}
