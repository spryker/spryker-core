<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_ROUTER = 'FACADE_ROUTER';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableHttpDataRequestExecutorApplicationPlugin::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR
     */
    public const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR = 'gui_table_http_data_request_executor';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableFactoryApplicationPlugin::SERVICE_GUI_TABLE_FACTORY
     */
    public const SERVICE_GUI_TABLE_FACTORY = 'gui_table_factory';

    public const PROPEL_QUERY_PRODUCT_CONCRETE = 'PROPEL_QUERY_PRODUCT_CONCRETE';
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';
    public const PROPEL_QUERY_PRODUCT_OFFER = 'PROPEL_QUERY_PRODUCT_OFFER';
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';
    public const PROPEL_QUERY_PRODUCT_OFFER_STORE = 'PROPEL_PRODUCT_OFFER_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addMerchantStockFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addRouterFacade($container);
        $container = $this->addTwigEnvironment($container);
        $container = $this->addGuiTableHttpDataRequestHandler($container);
        $container = $this->addGuiTableFactory($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductConcretePropelQuery($container);
        $container = $this->addProductImagePropelQuery($container);
        $container = $this->addProductOfferPropelQuery($container);
        $container = $this->addStorePropelQuery($container);
        $container = $this->addProductOfferStorePropelQuery($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

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
            return new ProductOfferMerchantPortalGuiToUtilEncodingServiceBridge(
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
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade()
            );
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
            return new ProductOfferMerchantPortalGuiToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
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
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
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
        $container->set(static::FACADE_PRODUCT_OFFER, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade()
            );
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
        $container->set(static::FACADE_MERCHANT_STOCK, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToMerchantStockFacadeBridge(
                $container->getLocator()->merchantStock()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterFacade(Container $container): Container
    {
        $container->set(static::FACADE_ROUTER, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToRouterFacadeBridge(
                $container->getLocator()->router()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigEnvironment(Container $container): Container
    {
        $container->set(static::SERVICE_TWIG, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableHttpDataRequestHandler(Container $container): Container
    {
        $container->set(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableFactory(Container $container): Container
    {
        $container->set(static::SERVICE_GUI_TABLE_FACTORY, $container->factory(function (Container $container) {
            return $container->getApplicationService(static::SERVICE_GUI_TABLE_FACTORY);
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcretePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_CONCRETE, $container->factory(function () {
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
        $container->set(static::PROPEL_QUERY_PRODUCT_IMAGE, $container->factory(function () {
            return SpyProductImageQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferPropelQuery(Container $container): Container
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
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER_STORE, $container->factory(function () {
            return SpyProductOfferStoreQuery::create();
        }));

        return $container;
    }
}
