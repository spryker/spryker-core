<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport;

use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\ContentProductSetDataImport\Dependency\Facade\ContentProductSetDataImportToContentBridge;
use Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportConfig getConfig()
 */
class ContentProductSetDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_CONTENT = 'FACADE_CONTENT';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PROPEL_QUERY_PRODUCT_SET = 'PROPEL_QUERY_PRODUCT_SET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);
        $container = $this->addContentFacade($container);
        $container = $this->addProductQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ContentProductSetDataImportToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentFacade(Container $container): Container
    {
        $container[static::FACADE_CONTENT] = function (Container $container) {
            return new ContentProductSetDataImportToContentBridge($container->getLocator()->content()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_SET] = function () {
            return SpyProductSetQuery::create();
        };

        return $container;
    }
}
