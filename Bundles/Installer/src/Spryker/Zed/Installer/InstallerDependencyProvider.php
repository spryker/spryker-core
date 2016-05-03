<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer;

use Spryker\Zed\Acl\Communication\Plugin\Installer as AclInstallerPlugin;
use Spryker\Zed\Collector\Communication\Plugin\Installer as CollectorInstallerPlugin;
use Spryker\Zed\Country\Communication\Plugin\Installer as CountryInstallerPlugin;
use Spryker\Zed\Glossary\Communication\Plugin\Installer as GlossaryInstallerPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Communication\Plugin\Installer as LocaleInstallerPlugin;
use Spryker\Zed\Newsletter\Communication\Plugin\Installer as NewsletterInstallerPlugin;
use Spryker\Zed\Price\Communication\Plugin\Installer as PriceInstallerPlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\Installer as ProductSearchInstallerPlugin;
use Spryker\Zed\Product\Communication\Plugin\Installer as ProductInstallerPlugin;
use Spryker\Zed\User\Communication\Plugin\Installer as UserInstallerPlugin;

class InstallerDependencyProvider extends AbstractBundleDependencyProvider
{

    const INSTALLER_PLUGINS = 'installer plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::INSTALLER_PLUGINS] = function (Container $container) {
            return $this->getInstallerPlugins();
        };

        return $container;
    }

    /**
     * Overwrite on project level.
     *
     * @return \Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin[]
     */
    public function getInstallerPlugins()
    {
        return [
            new CollectorInstallerPlugin(),
            new ProductInstallerPlugin(),
            new PriceInstallerPlugin(),
            new LocaleInstallerPlugin(),
            new CountryInstallerPlugin(),
            new UserInstallerPlugin(),
            new AclInstallerPlugin(),
            new NewsletterInstallerPlugin(),
            new ProductSearchInstallerPlugin(),
            new GlossaryInstallerPlugin(),
        ];
    }

}
