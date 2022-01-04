<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapter;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantStockFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToOmsFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductValidityFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 */
class ProductMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CATEGORY = 'CATEGORY FACADE';

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
    public const FACADE_MERCHANT_PRODUCT = 'FACADE_MERCHANT_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT_VOLUME = 'FACADE_PRICE_PRODUCT_VOLUME';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_VALIDITY = 'FACADE_PRODUCT_VALIDITY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_VOLUME = 'SERVICE_PRICE_PRODUCT_VOLUME';

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
    public const PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT = 'PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_CONCRETE = 'PROPEL_QUERY_PRODUCT_CONCRETE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_CATEGORY = 'PROPEL_QUERY_PRODUCT_CATEGORY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRICE_PRODUCT_DEFAULT = 'PROPEL_QUERY_PRICE_PRODUCT_DEFAULT';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_TABLE_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_TABLE_EXPANDER';

    /**
     * @var string
     */
    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';

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
        $container = $this->addGuiTableHttpDataRequestHandler($container);
        $container = $this->addGuiTableFactory($container);
        $container = $this->addZedUiFactory($container);
        $container = $this->addCategoryFacade($container);
        $container = $this->addMerchantProductFacade($container);
        $container = $this->addProductCategoryFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addPriceProductVolumeFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductFacade($container);
        $container = $this->addProductValidityFacade($container);
        $container = $this->addProductAttributeFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addMerchantStockFacade($container);
        $container = $this->addPriceProductVolumeService($container);

        $container = $this->addProductAbstractFormExpanderPlugins($container);
        $container = $this->addProductConcreteTableExpanderPlugins($container);
        $container = $this->addProductAttributeFacade($container);

        $container = $this->addValidationAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addMerchantProductAbstractPropelQuery($container);
        $container = $this->addProductImagePropelQuery($container);
        $container = $this->addProductConcretePropelQuery($container);
        $container = $this->addStorePropelQuery($container);
        $container = $this->addProductCategoryPropelQuery($container);
        $container = $this->addPriceProductDefaultPropelQuery($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addPriceProductFacade($container);

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
            return new ProductMerchantPortalGuiToLocaleFacadeBridge(
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
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new ProductMerchantPortalGuiToMerchantUserFacadeBridge(
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
            return new ProductMerchantPortalGuiToTranslatorFacadeBridge(
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
            return new ProductMerchantPortalGuiToStoreFacadeBridge(
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
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductMerchantPortalGuiToUtilEncodingServiceBridge(
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
            return new ProductMerchantPortalGuiToPriceProductVolumeServiceBridge(
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
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new ProductMerchantPortalGuiToCategoryFacadeBridge(
                $container->getLocator()->category()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT, function (Container $container) {
            return new ProductMerchantPortalGuiToMerchantProductFacadeBridge(
                $container->getLocator()->merchantProduct()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductMerchantPortalGuiToProductCategoryFacadeBridge(
                $container->getLocator()->productCategory()->facade(),
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
            return new ProductMerchantPortalGuiToPriceProductFacadeBridge(
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
    protected function addPriceProductVolumeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT_VOLUME, function (Container $container) {
            return new ProductMerchantPortalGuiToPriceProductVolumeFacadeBridge(
                $container->getLocator()->priceProductVolume()->facade(),
            );
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
            return new ProductMerchantPortalGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
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
            return new ProductMerchantPortalGuiToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
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
            return new ProductMerchantPortalGuiToProductFacadeBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductValidityFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_VALIDITY, function (Container $container) {
            return new ProductMerchantPortalGuiToProductValidityFacadeBridge($container->getLocator()->productValidity()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductMerchantPortalGuiToProductAttributeFacadeBridge(
                $container->getLocator()->productAttribute()->facade(),
            );
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
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new ProductMerchantPortalGuiToOmsFacadeBridge($container->getLocator()->oms()->facade());
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
            return new ProductMerchantPortalGuiToMerchantStockFacadeBridge(
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
    protected function addMerchantProductAbstractPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT, $container->factory(function () {
            return SpyMerchantProductAbstractQuery::create();
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
    protected function addProductCategoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_CATEGORY, $container->factory(function () {
            return SpyProductCategoryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductDefaultPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRICE_PRODUCT_DEFAULT, $container->factory(function () {
            return SpyPriceProductDefaultQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER, function () {
            return $this->getProductAbstractFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface>
     */
    protected function getProductAbstractFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_TABLE_EXPANDER, function () {
            return $this->getProductConcreteTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface>
     */
    protected function getProductConcreteTableExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_VALIDATION, function () {
            return new ProductMerchantPortalGuiToValidationAdapter();
        });

        return $container;
    }
}
