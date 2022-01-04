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
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToKernelFacadeBridge;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeBridge;
use Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreBridge;

/**
 * @method \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_KERNEL = 'FACADE_KERNEL';

    /**
     * @var string
     */
    public const ADAPTER_FINDER = 'ADAPTER_FINDER';

    /**
     * @var string
     */
    public const ADAPTER_FILESYSTEM = 'ADAPTER_FILESYSTEM';

    /**
     * @var string
     */
    public const ADAPTER_YAML = 'ADAPTER_YAML';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
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
        $container = $this->addStoreFacade($container);
        $container = $this->addKernelFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFinderAdapterDependency(Container $container): Container
    {
        $container->set(static::ADAPTER_FINDER, function () {
            return new RestRequestValidatorToFinderAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystemAdapterDependency(Container $container): Container
    {
        $container->set(static::ADAPTER_FILESYSTEM, function () {
            return new RestRequestValidatorToFilesystemAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYamlAdapterDependency(Container $container): Container
    {
        $container->set(static::ADAPTER_YAML, function () {
            return new RestRequestValidatorToYamlAdapter();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function (Container $container) {
            return new RestRequestValidatorToStoreBridge(Store::getInstance());
        });

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
            return new RestRequestValidatorToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addKernelFacade(Container $container): Container
    {
        $container->set(static::FACADE_KERNEL, function (Container $container) {
            return new RestRequestValidatorToKernelFacadeBridge(
                $container->getLocator()->kernel()->facade(),
            );
        });

        return $container;
    }
}
