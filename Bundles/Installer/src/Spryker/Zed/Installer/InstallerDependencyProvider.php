<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer;

use Spryker\Zed\Installer\Dependency\Facade\InstallerToGlossaryBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;

class InstallerDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_GLOSSARY = 'facade_glossary';
    const INSTALLERS = 'installer plugins';
    const INSTALLERS_DEMO_DATA = 'demo data installer plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new InstallerToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::INSTALLERS] = function (Container $container) {
            return $this->getInstallers();
        };
        $container[self::INSTALLERS_DEMO_DATA] = function (Container $container) {
            return $this->getDemoDataInstallers();
        };

        return $container;
    }

    /**
     * Overwrite on project level.
     *
     * @return AbstractInstaller[]
     */
    public function getInstallers()
    {
        return [];
    }

    /**
     * Overwrite on project level.
     *
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstallers()
    {
        return [];
    }

}
