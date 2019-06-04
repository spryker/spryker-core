<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductQuantityDataImport\Dependency\Service\ProductQuantityDataImportToProductQuantityServiceBridge;

/**
 * @method \Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig getConfig()
 */
class ProductQuantityDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const SERVICE_PRODUCT_QUANTITY = 'SERVICE_PRODUCT_QUANTITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQuantityService(Container $container): Container
    {
        $container[static::SERVICE_PRODUCT_QUANTITY] = function (Container $container) {
            return new ProductQuantityDataImportToProductQuantityServiceBridge(
                $container->getLocator()->productQuantity()->service()
            );
        };

        return $container;
    }
}
