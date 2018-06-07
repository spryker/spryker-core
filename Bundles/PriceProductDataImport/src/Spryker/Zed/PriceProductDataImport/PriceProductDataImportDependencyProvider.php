<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceBridge;

class PriceProductDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new PriceProductDataImportToPriceProductFacadeBridge($container->getLocator()->priceProduct()->facade());
        };

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
            return new PriceProductDataImportToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
