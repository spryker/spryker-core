<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity;

use Propel\Runtime\Propel;
use Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionAdapter;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DynamicEntityDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CONNECTION = 'CONNECTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addConnection($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConnection(Container $container): Container
    {
        $container->set(static::CONNECTION, function () {
            return new DynamicEntityToConnectionAdapter(Propel::getConnection());
        });

        return $container;
    }
}
