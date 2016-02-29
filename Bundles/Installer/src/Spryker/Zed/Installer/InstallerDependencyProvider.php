<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer;

use Spryker\Zed\Installer\Dependency\Facade\InstallerToGlossaryBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class InstallerDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_GLOSSARY = 'facade_glossary';
    const INSTALLERS = 'installer plugins';

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

        return $container;
    }

    /**
     * Overwrite on project level.
     *
     * @return \Spryker\Zed\Installer\Business\Model\AbstractInstaller[]
     */
    public function getInstallers()
    {
        return [];
    }

}
