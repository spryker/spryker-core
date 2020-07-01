<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation;

use Spryker\Zed\ContentNavigation\Dependency\External\ContentNavigationToValidationAdapter;
use Spryker\Zed\ContentNavigation\Dependency\External\ContentNavigationToValidationAdapterInterface;
use Spryker\Zed\ContentNavigation\Dependency\Facade\ContentNavigationToNavigationFacadeBridge;
use Spryker\Zed\ContentNavigation\Dependency\Facade\ContentNavigationToNavigationFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentNavigation\ContentNavigationConfig getConfig()
 */
class ContentNavigationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_NAVIGATION = 'FACADE_NAVIGATION';

    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addNavigationFacade($container);
        $container = $this->addValidationAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNavigationFacade(Container $container): Container
    {
        $container->set(static::FACADE_NAVIGATION, function (Container $container): ContentNavigationToNavigationFacadeInterface {
            return new ContentNavigationToNavigationFacadeBridge(
                $container->getLocator()->navigation()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_VALIDATION, function (): ContentNavigationToValidationAdapterInterface {
            return new ContentNavigationToValidationAdapter();
        });

        return $container;
    }
}
