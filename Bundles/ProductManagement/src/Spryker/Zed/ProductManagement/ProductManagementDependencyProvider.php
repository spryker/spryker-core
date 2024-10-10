<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBundleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductCategoryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTaxBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchBridge;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingBridge;
use Spryker\Zed\ProductManagement\Exception\MissingMoneyTypePluginException;
use Spryker\Zed\ProductManagement\Exception\MissingStoreRelationFormTypePluginException;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 */
class ProductManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_BUNDLE = 'FACADE_PRODUCT_BUNDLE';

    /**
     * @var string
     */
    public const FACADE_TOUCH = 'FACADE_TOUCH';

    /**
     * @var string
     */
    public const FACADE_TAX = 'FACADE_TAX';

    /**
     * @var string
     */
    public const FACADE_PRICE = 'FACADE_PRICE';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_STOCK = 'FACADE_STOCK';

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
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_STOCK = 'QUERY_CONTAINER_STOCK';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_GROUP = 'QUERY_CONTAINER_PRODUCT_GROUP';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_VIEW = 'PRODUCT_MANAGEMENT:PLUGINS_PRODUCT_ABSTRACT_VIEW';

    /**
     * @var string
     */
    public const PLUGIN_STORE_RELATION_FORM_TYPE = 'PLUGIN_STORE_RELATION_FORM_TYPE';

    /**
     * @var string
     */
    public const PLUGIN_MONEY_FORM_TYPE = 'MONEY_FORM_TYPE_PLUGIN';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS = 'PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS = 'PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS = 'PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_FORM_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_FORM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_FORM_EDIT_TABS_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_FORM_EDIT_TABS_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_EDIT_VIEW_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_EDIT_VIEW_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_EDIT_VIEW_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_EDIT_VIEW_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_LIST_ACTION_VIEW_DATA_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_LIST_ACTION_VIEW_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_VIEW_ACTION_VIEW_DATA_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_VIEW_ACTION_VIEW_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_QUERY_CRITERIA_EXPANDER = 'PLUGINS_PRODUCT_TABLE_QUERY_CRITERIA_EXPANDER';

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductManagement\ProductManagementDependencyProvider::PLUGINS_PRODUCT_TABLE_DATA_BULK_EXPANDER} instead.
     *
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_DATA_EXPANDER = 'PLUGINS_PRODUCT_TABLE_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_CONFIGURATION_EXPANDER = 'PLUGINS_PRODUCT_TABLE_CONFIGURATION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_DATA_BULK_EXPANDER = 'PLUGINS_PRODUCT_TABLE_DATA_BULK_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_TABLE_ACTION_EXPANDER = 'PLUGINS_PRODUCT_TABLE_ACTION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_VARIANT_TABLE_ACTION_EXPANDER = 'PLUGINS_PRODUCT_VARIANT_TABLE_ACTION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductFacade($container);

        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        });

        $container->set(static::FACADE_TAX, function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        });

        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        });

        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductManagementToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        });

        $container = $this->addStockFacade($container);

        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        });

        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductManagementToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_STOCK, function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        });

        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addProductFacade($container);
        $container = $this->addProductBundleFacade($container);

        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductManagementToProductCategoryBridge($container->getLocator()->productCategory()->facade());
        });

        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        });

        $container->set(static::FACADE_TAX, function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        });

        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductManagementToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        });

        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        });

        $container = $this->addStockFacade($container);

        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new ProductManagementToMoneyBridge($container->getLocator()->money()->facade());
        });

        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new ProductManagementToCurrencyBridge($container->getLocator()->currency()->facade());
        });

        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_STOCK, function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_GROUP, function (Container $container) {
            return $container->getLocator()->productGroup()->queryContainer();
        });

        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductManagementToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        $container->set(static::FACADE_AVAILABILITY, function (Container $container) {
            return new ProductManagementToAvailabilityBridge($container->getLocator()->availability()->facade());
        });

        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductManagementToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductManagementToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        });

        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
        });

        $container = $this->addStockFacade($container);
        $container = $this->addProductAbstractViewPlugins($container);
        $container = $this->addStoreRelationFormTypePlugin($container);
        $container = $this->addMoneyFormTypePlugin($container);
        $container = $this->addProductConcreteEditFormExpanderPlugins($container);
        $container = $this->addProductConcreteFormEditDataProviderExpanderPlugins($container);
        $container = $this->addProductFormTransferMapperExpanderPlugins($container);
        $container = $this->addProductConcreteFormEditTabsExpanderPlugins($container);
        $container = $this->addProductAbstractFormExpanderPlugins($container);
        $container = $this->addProductConcreteFormExpanderPlugins($container);
        $container = $this->addProductAbstractFormEditTabsExpanderPlugins($container);
        $container = $this->addProductAbstractEditViewExpanderPlugins($container);
        $container = $this->addProductConcreteEditViewExpanderPlugins($container);
        $container = $this->addProductAbstractListActionViewDataExpanderPlugins($container);
        $container = $this->addProductAbstractViewActionViewDataExpanderPlugins($container);
        $container = $this->addProductTableDataExpanderPlugins($container);
        $container = $this->addProductTableConfigurationExpanderPlugins($container);
        $container = $this->addProductTableDataBulkExpanderPlugins($container);
        $container = $this->addProductTableActionExpanderPlugins($container);
        $container = $this->addProductVariantTableActionExpanderPlugins($container);

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

        $container = $this->addProductTableQueryCriteriaExpanderPluginInterfaces($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractViewPlugins(Container $container)
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_VIEW, function () {
            return $this->getProductAbstractViewPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFormTypePlugin(Container $container)
    {
        $container->set(static::PLUGIN_MONEY_FORM_TYPE, function (Container $container) {
            return $this->createMoneyFormTypePlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Zed\ProductManagement\Exception\MissingMoneyTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function createMoneyFormTypePlugin(Container $container)
    {
        throw new MissingMoneyTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure MoneyFormTypePlugin ' .
                'in your own ProductManagementDependencyProvider::createMoneyFormTypePlugin() ' .
                'to be able to manage product prices.',
                FormTypeInterface::class,
            ),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface>
     */
    protected function getProductAbstractViewPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreRelationFormTypePlugin(Container $container)
    {
        $container->set(static::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return $this->getStoreRelationFormTypePlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Zed\ProductManagement\Exception\MissingStoreRelationFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin()
    {
        throw new MissingStoreRelationFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure StoreRelationFormType ' .
                'in your own ProductManagementDependencyProvider::getStoreRelationFormTypePlugin() ' .
                'to be able to manage shipment prices.',
                FormTypeInterface::class,
            ),
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductManagementToProductBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_BUNDLE, function (Container $container) {
            return new ProductManagementToProductBundleBridge($container->getLocator()->productBundle()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container)
    {
        $container->set(static::FACADE_STOCK, function (Container $container) {
            return new ProductManagementToStockBridge($container->getLocator()->stock()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER, function (Container $container) {
            return $this->getProductAbstractFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface>
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
    protected function addProductConcreteFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_FORM_EXPANDER, function (Container $container) {
            return $this->getProductConcreteFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormExpanderPluginInterface>
     */
    protected function getProductConcreteFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteEditFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getProductConcreteEditFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditFormExpanderPluginInterface>
     */
    protected function getProductConcreteEditFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteFormEditDataProviderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getProductConcreteFormEditDataProviderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface>
     */
    protected function getProductConcreteFormEditDataProviderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFormTransferMapperExpanderPlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getProductFormTransferMapperExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface>
     */
    protected function getProductFormTransferMapperExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteFormEditTabsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER, function (Container $container) {
            return $this->getProductConcreteFormEditTabsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface>
     */
    protected function getProductConcreteFormEditTabsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractFormEditTabsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_FORM_EDIT_TABS_EXPANDER, function () {
            return $this->getProductAbstractFormEditTabsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormEditTabsExpanderPluginInterface>
     */
    protected function getProductAbstractFormEditTabsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractEditViewExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_EDIT_VIEW_EXPANDER, function () {
            return $this->getProductAbstractEditViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface>
     */
    protected function getProductAbstractEditViewExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteEditViewExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_EDIT_VIEW_EXPANDER, function () {
            return $this->getProductConcreteEditViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditViewExpanderPluginInterface>
     */
    protected function getProductConcreteEditViewExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractListActionViewDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_LIST_ACTION_VIEW_DATA_EXPANDER, function () {
            return $this->getProductAbstractListActionViewDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractListActionViewDataExpanderPluginInterface>
     */
    protected function getProductAbstractListActionViewDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractViewActionViewDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_VIEW_ACTION_VIEW_DATA_EXPANDER, function () {
            return $this->getProductAbstractViewActionViewDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractViewActionViewDataExpanderPluginInterface>
     */
    protected function getProductAbstractViewActionViewDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableQueryCriteriaExpanderPluginInterfaces(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_QUERY_CRITERIA_EXPANDER, function () {
            return $this->getProductTableQueryCriteriaExpanderPluginInterfaces();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableQueryCriteriaExpanderPluginInterface>
     */
    protected function getProductTableQueryCriteriaExpanderPluginInterfaces(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductManagement\ProductManagementDependencyProvider::addProductTableDataBulkExpanderPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_DATA_EXPANDER, function () {
            return $this->getProductTableDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductManagement\ProductManagementDependencyProvider::getProductTableDataBulkExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataExpanderPluginInterface>
     */
    protected function getProductTableDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableConfigurationExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_CONFIGURATION_EXPANDER, function (): array {
            return $this->getProductTableConfigurationExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableConfigurationExpanderPluginInterface>
     */
    protected function getProductTableConfigurationExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableDataBulkExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_DATA_BULK_EXPANDER, function (): array {
            return $this->getProductTableDataBulkExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataBulkExpanderPluginInterface>
     */
    protected function getProductTableDataBulkExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductTableActionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_TABLE_ACTION_EXPANDER, function (): array {
            return $this->getProductTableActionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableActionExpanderPluginInterface>
     */
    protected function getProductTableActionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductManagementToStoreFacadeBridge(
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
    protected function addProductVariantTableActionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_VARIANT_TABLE_ACTION_EXPANDER, function (): array {
            return $this->getProductVariantTableActionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductVariantTableActionExpanderPluginInterface>
     */
    protected function getProductVariantTableActionExpanderPlugins(): array
    {
        return [];
    }
}
