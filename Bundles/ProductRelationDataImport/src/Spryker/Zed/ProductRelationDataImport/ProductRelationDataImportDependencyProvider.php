<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeBridge;
use Spryker\Zed\ProductRelationDataImport\Dependency\Service\ProductRelationDataImportToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig getConfig()
 */
class ProductRelationDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_PRODUCT_RELATION = 'FACADE_PRODUCT_RELATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductRelationFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductRelationDataImportToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_RELATION, function (Container $container) {
            return new ProductRelationDataImportToProductRelationFacadeBridge(
                $container->getLocator()->productRelation()->facade()
            );
        });

        return $container;
    }
}
