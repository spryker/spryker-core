<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample;

use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\ProductOfferWarehouseAllocationExampleConfig getConfig()
 */
class ProductOfferWarehouseAllocationExampleDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OFFER_STOCK = 'PROPEL_QUERY_PRODUCT_OFFER_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addProductOfferStockPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferStockPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER_STOCK, $container->factory(function () {
            return SpyProductOfferStockQuery::create();
        }));

        return $container;
    }
}
