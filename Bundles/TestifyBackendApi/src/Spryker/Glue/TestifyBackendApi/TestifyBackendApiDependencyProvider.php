<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapter;
use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeBridge;
use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeBridge;

/**
 * @method \Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig getConfig()
 */
class TestifyBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_QUEUE = 'FACADE_QUEUE';

    /**
     * @var string
     */
    public const ADAPTER_CODECEPTION = 'ADAPTER_CODECEPTION';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addEventBehaviourFacade($container);
        $container = $this->addQueueFacade($container);
        $container = $this->addCodeceptionAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addEventBehaviourFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new TestifyBackendApiToEventBehaviourFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addQueueFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUEUE, function (Container $container) {
            return new TestifyBackendApiToQueueFacadeBridge($container->getLocator()->queue()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addCodeceptionAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_CODECEPTION, function () {
            return new TestifyBackendApiToCodeceptionAdapter();
        });

        return $container;
    }
}
