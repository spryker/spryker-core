<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui;

use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Spryker\Zed\DynamicEntityGui\Dependency\External\DynamicEntityGuiToInflectorAdapter;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig getConfig()
 */
class DynamicEntityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_DYNAMIC_ENTITY = 'PROPEL_QUERY_DYNAMIC_ENTITY';

    /**
     * @var string
     */
    public const FACADE_DYNAMIC_ENTITY = 'FACADE_DYNAMIC_ENTITY';

    /**
     * @var string
     */
    public const INFLECTOR = 'INFLECTOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addDynamicEntityPropelQuery($container);
        $container = $this->addDynamicEntityFacade($container);
        $container = $this->addInflector($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicEntityPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_DYNAMIC_ENTITY, $container->factory(function () {
            return SpyDynamicEntityConfigurationQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicEntityFacade(Container $container): Container
    {
        $container->set(static::FACADE_DYNAMIC_ENTITY, function (Container $container) {
            return new DynamicEntityGuiToDynamicEntityFacadeBridge(
                $container->getLocator()->dynamicEntity()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addInflector(Container $container): Container
    {
        $container->set(static::INFLECTOR, function () {
            return new DynamicEntityGuiToInflectorAdapter();
        });

        return $container;
    }
}
