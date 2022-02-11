<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @var string
     */
    public const FACADE_ROUTER = 'FACADE_ROUTER';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT_OFFER = 'FACADE_PRICE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT_OFFER_VOLUME = 'FACADE_PRICE_PRODUCT_OFFER_VOLUME';

    /**
     * @var string
     */
    public const EXTERNAL_ADAPTER_VALIDATION = 'EXTERNAL_ADAPTER_VALIDATION';

    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_VOLUME = 'SERVICE_PRICE_PRODUCT_VOLUME';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableApplicationPlugin::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR
     *
     * @var string
     */
    public const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR = 'gui_table_http_data_request_executor';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableApplicationPlugin::SERVICE_GUI_TABLE_FACTORY
     *
     * @var string
     */
    public const SERVICE_GUI_TABLE_FACTORY = 'gui_table_factory';

    /**
     * @uses \Spryker\Zed\ZedUi\Communication\Plugin\Application\ZedUiApplicationPlugin::SERVICE_ZED_UI_FACTORY
     *
     * @var string
     */
    public const SERVICE_ZED_UI_FACTORY = 'SERVICE_ZED_UI_FACTORY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_CONCRETE = 'PROPEL_QUERY_PRODUCT_CONCRETE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OFFER = 'PROPEL_QUERY_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OFFER_STORE = 'PROPEL_PRODUCT_OFFER_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRICE_PRODUCT_STORE = 'PROPEL_QUERY_PRICE_PRODUCT_STORE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_EXPANDER = 'PLUGINS_PRODUCT_TABLE_EXPANDER';

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
        $container = $this->addZedUiFactory($container);
        $container = $this->addPriceProductOfferFacade($container);
        $container = $this->addPriceProductOfferVolumeFacade($container);
        $container = $this->addValidationAdapter($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addPriceProductVolumeService($container);
        $container = $this->addProductTableExpanderPlugins($container);

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
        $container = $this->addPriceProductStorePropelQuery($container);
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
                $container->getLocator()->locale()->facade(),
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
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductVolumeService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT_VOLUME, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToPriceProductVolumeServiceBridge(
                $container->getLocator()->priceProductVolume()->service(),
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
                $container->getLocator()->merchantUser()->facade(),
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
                $container->getLocator()->translator()->facade(),
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
                $container->getLocator()->store()->facade(),
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
                $container->getLocator()->product()->facade(),
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
                $container->getLocator()->productOffer()->facade(),
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
                $container->getLocator()->merchantStock()->facade(),
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
                $container->getLocator()->currency()->facade(),
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
                $container->getLocator()->priceProduct()->facade(),
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
                $container->getLocator()->router()->facade(),
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
        $container->set(static::SERVICE_GUI_TABLE_FACTORY, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_GUI_TABLE_FACTORY);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedUiFactory(Container $container): Container
    {
        $container->set(static::SERVICE_ZED_UI_FACTORY, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ZED_UI_FACTORY);
        });

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRICE_PRODUCT_STORE, $container->factory(function () {
            return SpyPriceProductStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT_OFFER, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToPriceProductOfferFacadeBridge(
                $container->getLocator()->priceProductOffer()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferVolumeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT_OFFER_VOLUME, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeBridge(
                $container->getLocator()->priceProductOfferVolume()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::EXTERNAL_ADAPTER_VALIDATION, function () {
            return new ProductOfferMerchantPortalGuiToValidationAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToMoneyFacadeBridge(
                $container->getLocator()->money()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_EXPANDER, function (Container $container) {
            return $this->getProductTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface>
     */
    protected function getProductTableExpanderPlugins(): array
    {
        return [];
    }
}
