<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Propel\Runtime\Propel;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DataImportDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'touch facade';
    const PROPEL_CONNECTION = 'propel connection';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addTouchFacade($container);
        $this->addPropelConnection($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new DataImportToTouchBridge(
                $container->getLocator()->touch()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addPropelConnection(Container $container)
    {
        $container[static::PROPEL_CONNECTION] = function () {
            return new DataImportToPropelConnectionBridge(
                Propel::getConnection()
            );
        };
    }
}
