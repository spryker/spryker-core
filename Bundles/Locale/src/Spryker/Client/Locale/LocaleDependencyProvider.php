<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientBridge;
use Spryker\Shared\Kernel\Store;

class LocaleDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const LOCALE_CURRENT = 'LOCALE_CURRENT';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addCurrentLocale($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrentLocale(Container $container): Container
    {
        $container->set(static::LOCALE_CURRENT, function (Container $container) {
            if ($container->hasApplicationService(static::SERVICE_LOCALE)) {
                return $container->getApplicationService(static::SERVICE_LOCALE);
            }

            return $this->getStore()->getCurrentLocale();
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore(): Store
    {
        return Store::getInstance();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
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
}
