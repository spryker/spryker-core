<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Payment\Business\PaymentFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class SspServiceManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT = 'FACADE_SERVICE_POINT';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT_TYPE = 'FACADE_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_EVENT = 'FACADE_EVENT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_PAYMENT = 'FACADE_PAYMENT';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';

    /**
     * @var string
     */
    public const PROPEL_QUERY_SHIPMENT_TYPE = 'PROPEL_QUERY_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE = 'PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addSalesOrderItemQuery($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addServicePointFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addMerchantStockFacade($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductImagePropelQuery($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addShipmentTypeQuery($container);
        $container = $this->addProductShipmentTypeQuery($container);
        $container = $this->addProductAbstractQuery($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addServicePointFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        parent::providePersistenceLayerDependencies($container);

        $container = $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT_TYPE, static function (Container $container): ShipmentTypeFacadeInterface {
            return $container->getLocator()->shipmentType()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, static function (Container $container): LocaleFacadeInterface {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT, static function (Container $container): ServicePointFacadeInterface {
            return $container->getLocator()->servicePoint()->facade();
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
        $container->set(static::FACADE_STORE, static function (Container $container): StoreFacadeInterface {
            return $container->getLocator()->store()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT, static function (Container $container): EventFacadeInterface {
            return $container->getLocator()->event()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, static function (Container $container): ProductOfferFacadeInterface {
            return $container->getLocator()->productOffer()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, static function (Container $container): ProductFacadeInterface {
            return $container->getLocator()->product()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, static function (Container $container): MerchantFacadeInterface {
            return $container->getLocator()->merchant()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_STOCK, static function (Container $container): MerchantStockFacadeInterface {
            return $container->getLocator()->merchantStock()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function (): SpyProductQuery {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImagePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_IMAGE, $container->factory(function (): SpyProductImageQuery {
            return SpyProductImageQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_ITEM, $container->factory(function (Container $container): SpySalesOrderItemQuery {
            return SpySalesOrderItemQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SHIPMENT_TYPE, $container->factory(function (Container $container): SpyShipmentTypeQuery {
            return SpyShipmentTypeQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductShipmentTypeQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE, $container->factory(function (Container $container): SpyProductShipmentTypeQuery {
            return SpyProductShipmentTypeQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ABSTRACT, $container->factory(function (Container $container): SpyProductAbstractQuery {
            return SpyProductAbstractQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, static function (Container $container): SalesFacadeInterface {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, static function (Container $container): PaymentFacadeInterface {
            return $container->getLocator()->payment()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, static function (Container $container): OmsFacadeInterface {
            return $container->getLocator()->oms()->facade();
        });

        return $container;
    }
}
