<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui;

use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToLocaleFacadeBridge;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeBridge;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToStoreFacadeBridge;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToStoreReferenceFacadeBridge;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig getConfig()
 */
class AppCatalogGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const FACADE_OAUTH_CLIENT = 'FACADE_OAUTH_CLIENT';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_STORE_REFERENCE = 'FACADE_STORE_REFERENCE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addTranslatorFacade($container);
        $container = $this->addOauthClientFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addStoreReferenceFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new AppCatalogGuiToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreReferenceFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE_REFERENCE, function (Container $container) {
            return new AppCatalogGuiToStoreReferenceFacadeBridge($container->getLocator()->storeReference()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new AppCatalogGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new AppCatalogGuiToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthClientFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH_CLIENT, function (Container $container) {
            return new AppCatalogGuiToOauthClientFacadeBridge(
                $container->getLocator()->oauthClient()->facade(),
            );
        });

        return $container;
    }
}
