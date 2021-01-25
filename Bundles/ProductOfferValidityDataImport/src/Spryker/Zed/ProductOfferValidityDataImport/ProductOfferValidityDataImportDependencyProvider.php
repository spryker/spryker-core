<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportConfig getConfig()
 */
class ProductOfferValidityDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, function (Container $container) {
            return new ProductOfferValidityDataImportToProductOfferFacadeBridge($container->getLocator()->productOffer()->facade());
        });

        return $container;
    }
}
