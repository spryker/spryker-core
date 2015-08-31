<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\InstallerBusiness;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Installer\InstallerConfig;
use SprykerFeature\Zed\Installer\InstallerDependencyProvider;

/**
 * @method InstallerBusiness getFactory()
 * @method InstallerConfig getConfig()
 */
class InstallerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstaller()
    {
        return $this->getConfig()->getInstallerStack();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstaller()
    {
        return $this->getConfig()->getDemoDataInstallerStack();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getGlossaryInstaller()
    {
        return $this->getFactory()->createModelGlossaryInstaller(
            $this->getProvidedDependency(InstallerDependencyProvider::GLOSSARY_FACADE),
            $this->getConfig()->getGlossaryFilePaths()
        );
    }

}
