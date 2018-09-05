<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeBridge;

class RestRequestValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FINDER = 'FINDER';
    public const FILESYSTEM = 'FILESYSTEM';
    public const YAML = 'YAML';
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addFinderDependency($container);
        $container = $this->addFilesystemDependency($container);
        $container = $this->addYamlDependency($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFinderDependency(Container $container): Container
    {
        $container[self::FINDER] = function () {
            return new RestRequestValidatorToFinderAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystemDependency(Container $container): Container
    {
        $container[self::FILESYSTEM] = function () {
            return new RestRequestValidatorToFilesystemAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYamlDependency(Container $container)
    {
        $container[self::YAML] = function () {
            return new RestRequestValidatorToYamlAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new RestRequestValidatorToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }
}
