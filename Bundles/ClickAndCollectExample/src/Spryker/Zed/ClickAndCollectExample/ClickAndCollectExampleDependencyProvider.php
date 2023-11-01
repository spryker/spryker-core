<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample;

use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeBridge;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeBridge;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeBridge;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 */
class ClickAndCollectExampleDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OFFER = 'PROPEL_QUERY_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OFFER_STOCK = 'PROPEL_QUERY_PRODUCT_OFFER_STOCK';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRICE_PRODUCT_OFFER = 'PROPEL_QUERY_PRICE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT = 'FACADE_SERVICE_POINT';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addServicePointFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addAvailabilityFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addProductOfferQuery($container);
        $container = $this->addProductOfferStockQuery($container);
        $container = $this->addPriceProductOfferQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER, $container->factory(function () {
            return SpyProductOfferQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT, function (Container $container) {
            return new ClickAndCollectExampleToServicePointFacadeBridge($container->getLocator()->servicePoint()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new ClickAndCollectExampleToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ClickAndCollectExampleToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityFacade(Container $container): Container
    {
        $container->set(static::FACADE_AVAILABILITY, function (Container $container) {
            return new ClickAndCollectExampleToAvailabilityFacadeBridge($container->getLocator()->availability()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferStockQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER_STOCK, $container->factory(function () {
            return SpyProductOfferStockQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRICE_PRODUCT_OFFER, $container->factory(function () {
            return SpyPriceProductOfferQuery::create();
        }));

        return $container;
    }
}
