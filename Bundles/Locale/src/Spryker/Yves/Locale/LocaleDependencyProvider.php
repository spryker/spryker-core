<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientBridge;
use Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin;

/**
 * @method \Spryker\Yves\Locale\LocaleConfig getConfig()
 */
class LocaleDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGIN_LOCALE = 'PLUGIN_LOCALE';

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addLocalePlugin($container);
        $container = $this->addStore($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addLocalePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_LOCALE, function () {
            return $this->getLocalePlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface
     */
    public function getLocalePlugin(): LocalePluginInterface
    {
        return new LocaleLocalePlugin();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new LocaleToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addStore(Container $container): Container
    {
        $container->set(static::STORE, function () {
            return $this->getStore();
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return Store::getInstance();
    }
}
