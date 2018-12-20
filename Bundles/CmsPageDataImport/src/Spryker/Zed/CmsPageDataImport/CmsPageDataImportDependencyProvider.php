<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageDataImport;

use Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsPageDataImport\CmsPageDataImportConfig getConfig()
 */
class CmsPageDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_CMS = 'cms facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addCmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsFacade(Container $container): Container
    {
        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsPageDataImportToCmsFacadeBridge($container->getLocator()->cms()->facade());
        };

        return $container;
    }
}
