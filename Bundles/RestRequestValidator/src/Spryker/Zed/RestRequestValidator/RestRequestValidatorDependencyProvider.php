<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreBridge;

/**
 * @method \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const ADAPTER_FINDER = 'ADAPTER_FINDER';
    public const ADAPTER_FILESYSTEM = 'ADAPTER_FILESYSTEM';
    public const ADAPTER_YAML = 'ADAPTER_YAML';
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addFinderAdapterDependency($container);
        $container = $this->addFilesystemAdapterDependency($container);
        $container = $this->addYamlAdapterDependency($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFinderAdapterDependency(Container $container): Container
    {
        $container[static::ADAPTER_FINDER] = function () {
            return new RestRequestValidatorToFinderAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystemAdapterDependency(Container $container): Container
    {
        $container[static::ADAPTER_FILESYSTEM] = function () {
            return new RestRequestValidatorToFilesystemAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYamlAdapterDependency(Container $container): Container
    {
        $container[static::ADAPTER_YAML] = function () {
            return new RestRequestValidatorToYamlAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function (Container $container) {
            return new RestRequestValidatorToStoreBridge(Store::getInstance());
        };

        return $container;
    }
}
