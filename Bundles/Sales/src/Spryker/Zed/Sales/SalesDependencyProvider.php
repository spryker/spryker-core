<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToTranslatorBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilUuidGeneratorBridge;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const SERVICE_DATE_FORMATTER = 'SERVICE_DATE_FORMATTER';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

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
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const PROPEL_QUERY_LOCALE = 'PROPEL_QUERY_LOCALE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_OMS_ORDER_ITEM_STATE = 'PROPEL_QUERY_OMS_ORDER_ITEM_STATE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const ORDER_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_EXPANDER_PRE_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const HYDRATE_ORDER_PLUGINS = 'HYDRATE_ORDER_PLUGINS';

    /**
     * @var string
     */
    public const ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const ITEM_TRANSFORMER_STRATEGY_PLUGINS = 'ITEM_TRANSFORMER_STRATEGY_PLUGINS';

    /**
     * @var string
     */
    public const UI_SALES_TABLE_PLUGINS = 'UI_SALES_TABLE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_SAVE = 'PLUGINS_ORDER_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT = 'PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC = 'PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC';

    /**
     * @var string
     */
    public const PLUGINS_ITEM_PRE_TRANSFORMER = 'PLUGINS_ITEM_PRE_TRANSFORMER';

    /**
     * @var string
     */
    public const PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER = 'PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_EXPANDER = 'PLUGINS_ORDER_ITEM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_ORDER_EXPANDER = 'PLUGINS_SEARCH_ORDER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_SEARCH_QUERY_EXPANDER = 'PLUGINS_ORDER_SEARCH_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK = 'PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_TABLE_EXPANDER = 'PLUGINS_ORDER_ITEMS_TABLE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_POST_SAVE = 'PLUGINS_ORDER_ITEMS_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_UPDATE = 'PLUGINS_ORDER_POST_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_CANCEL = 'PLUGINS_ORDER_POST_CANCEL';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_PRE_CREATE = 'PLUGINS_ORDER_ITEMS_PRE_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_PRE_UPDATE = 'PLUGINS_ORDER_ITEMS_PRE_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_SALES_EXPENSE_PRE_DELETE = 'PLUGINS_SALES_EXPENSE_PRE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_SALES_ORDER_ITEM_COLLECTION_PRE_DELETE = 'PLUGINS_SALES_ORDER_ITEM_COLLECTION_PRE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_COLLECTION_POST_CREATE = 'PLUGINS_ORDER_ITEM_COLLECTION_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_COLLECTION_POST_UPDATE = 'PLUGINS_ORDER_ITEM_COLLECTION_POST_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER = 'PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT = 'PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT_ASYNC = 'PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT_ASYNC';

    /**
     * @deprecated Will be removed in the next major version.
     *
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @var string
     */
    public const SERVICE_UTIL_UUID_GENERATOR = 'SERVICE_UTIL_UUID_GENERATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addLocalePropelQuery($container);
        $container = $this->addOrderExpanderPreSavePlugins($container);
        $container = $this->addHydrateOrderPlugins($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addOrderItemExpanderPreSavePlugins($container);
        $container = $this->addItemTransformerStrategyPlugins($container);
        $container = $this->addOrderPostSavePlugins($container);
        $container = $this->addOrderPostSavePluginsForOrderAmendment($container);
        $container = $this->addOrderPostSavePluginsForOrderAmendmentAsync($container);
        $container = $this->addItemPreTransformerPlugins($container);
        $container = $this->addUniqueOrderItemsExpanderPlugins($container);
        $container = $this->addOrderItemExpanderPlugins($container);
        $container = $this->addSearchOrderExpanderPlugins($container);
        $container = $this->addOrderSearchQueryExpanderPlugins($container);
        $container = $this->addCustomerOrderAccessCheckPlugins($container);
        $container = $this->addOrderItemsPostSavePlugins($container);
        $container = $this->addOrderPostUpdatePlugins($container);
        $container = $this->addOrderPostCancelPlugins($container);
        $container = $this->addSalesExpensePreDeletePlugins($container);
        $container = $this->addOrderItemPreCreatePlugins($container);
        $container = $this->addOrderItemPreUpdatePlugins($container);
        $container = $this->addSalesOrderItemCollectionPreDeletePlugins($container);
        $container = $this->addOrderItemCollectionPostCreatePlugins($container);
        $container = $this->addOrderItemCollectionPostUpdatePlugins($container);
        $container = $this->addOrderItemInitialStateProviderPlugins($container);
        $container = $this->addOrderItemInitialStateProviderPluginsForOrderAmendment($container);
        $container = $this->addOrderItemInitialStateProviderPluginsForOrderAmendmentAsync($container);
        $container = $this->addUtilUuidGeneratorService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addDateTimeFormatter($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMoneyPlugin($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addSalesTablePlugins($container);
        $container = $this->addOrderItemsTableExpanderPlugins($container);
        $container = $this->addCsrfProviderService($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addTranslatorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addOmsOrderItemStatePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderExpanderPreSavePlugins(Container $container)
    {
        $container->set(static::ORDER_EXPANDER_PRE_SAVE_PLUGINS, function (Container $container) {
            return $this->getOrderExpanderPreSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addHydrateOrderPlugins(Container $container)
    {
        $container->set(static::HYDRATE_ORDER_PLUGINS, function (Container $container) {
            return $this->getOrderHydrationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemExpanderPreSavePlugins(Container $container)
    {
        $container->set(static::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS, function (Container $container) {
            return $this->getOrderItemExpanderPreSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemTransformerStrategyPlugins(Container $container): Container
    {
        $container->set(static::ITEM_TRANSFORMER_STRATEGY_PLUGINS, function (Container $container) {
            return $this->getItemTransformerStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesTablePlugins(Container $container)
    {
        $container->set(static::UI_SALES_TABLE_PLUGINS, function (Container $container) {
            return $this->getSalesTablePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new SalesToMoneyBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container)
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container)
    {
        $container->set(static::FACADE_SEQUENCE_NUMBER, function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container)
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new SalesToUserBridge($container->getLocator()->user()->facade());
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
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new SalesToLocaleBridge($container->getLocator()->locale()->facade());
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
            return new SalesToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new SalesToCustomerBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeFormatter(Container $container)
    {
        $container->set(static::SERVICE_DATE_FORMATTER, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocalePropelQuery(Container $container)
    {
        $container->set(static::PROPEL_QUERY_LOCALE, $container->factory(function (Container $container) {
            return SpyLocaleQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsOrderItemStatePropelQuery(Container $container): Container
    {
        $container->set(
            static::PROPEL_QUERY_OMS_ORDER_ITEM_STATE,
            $container->factory(fn () => SpyOmsOrderItemStateQuery::create()),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new SalesToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new SalesToCalculationBridge($container->getLocator()->calculation()->facade());
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
        $container->set(
            static::FACADE_TRANSLATOR,
            fn (Container $container) => new SalesToTranslatorBridge($container->getLocator()->translator()->facade()),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilUuidGeneratorService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_UUID_GENERATOR, function (Container $container) {
            return new SalesToUtilUuidGeneratorBridge($container->getLocator()->utilUuidGenerator()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_SAVE, function () {
            return $this->getOrderPostSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostSavePluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT, function () {
            return $this->getOrderPostSavePluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostSavePluginsForOrderAmendmentAsync(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC, function () {
            return $this->getOrderPostSavePluginsForOrderAmendmentAsync();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemPreTransformerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ITEM_PRE_TRANSFORMER, function () {
            return $this->getItemPreTransformerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUniqueOrderItemsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER, function () {
            return $this->getUniqueOrderItemsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_EXPANDER, function () {
            return $this->getOrderItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchOrderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_ORDER_EXPANDER, function () {
            return $this->getSearchOrderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_SEARCH_QUERY_EXPANDER, function () {
            return $this->getOrderSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerOrderAccessCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK, function () {
            return $this->getCustomerOrderAccessCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemsTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_TABLE_EXPANDER, function () {
            return $this->getOrderItemsTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemsPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_POST_SAVE, function () {
            return $this->getOrderItemsPostSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_UPDATE, function () {
            return $this->getOrderPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostCancelPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_CANCEL, function () {
            return $this->getOrderPostCancelPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesExpensePreDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SALES_EXPENSE_PRE_DELETE, function () {
            return $this->getSalesExpensePreDeletePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemCollectionPreDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SALES_ORDER_ITEM_COLLECTION_PRE_DELETE, function () {
            return $this->getSalesOrderItemCollectionPreDeletePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCsrfProviderService(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemPreCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_PRE_CREATE, function () {
            return $this->getOrderItemsPreCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemPreUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_PRE_UPDATE, function () {
            return $this->getOrderItemsPreUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemCollectionPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_COLLECTION_POST_CREATE, function () {
            return $this->getOrderItemCollectionPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemCollectionPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_COLLECTION_POST_UPDATE, function () {
            return $this->getOrderItemCollectionPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemInitialStateProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER, function () {
            return $this->getOrderItemInitialStateProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemInitialStateProviderPluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT, function () {
            return $this->getOrderItemInitialStateProviderPluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemInitialStateProviderPluginsForOrderAmendmentAsync(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT_ASYNC, function () {
            return $this->getOrderItemInitialStateProviderPluginsForOrderAmendmentAsync();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface>
     */
    protected function getOrderExpanderPreSavePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface>
     */
    protected function getOrderHydrationPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface>
     */
    protected function getOrderItemExpanderPreSavePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\ItemTransformerStrategyPluginInterface>
     */
    public function getItemTransformerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface>
     */
    protected function getSalesTablePlugins()
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>
     */
    protected function getOrderPostSavePlugins()
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>
     */
    protected function getOrderPostSavePluginsForOrderAmendment()
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>
     */
    protected function getOrderPostSavePluginsForOrderAmendmentAsync()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\ItemPreTransformerPluginInterface>
     */
    protected function getItemPreTransformerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface>
     */
    protected function getUniqueOrderItemsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface>
     */
    protected function getOrderItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface>
     */
    protected function getSearchOrderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface>
     */
    protected function getOrderSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface>
     */
    protected function getCustomerOrderAccessCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsTableExpanderPluginInterface>
     */
    protected function getOrderItemsTableExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface>
     */
    protected function getOrderItemsPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostUpdatePluginInterface>
     */
    protected function getOrderPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostCancelPluginInterface>
     */
    protected function getOrderPostCancelPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesExpensePreDeletePluginInterface>
     */
    protected function getSalesExpensePreDeletePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreCreatePluginInterface>
     */
    protected function getOrderItemsPreCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface>
     */
    protected function getSalesOrderItemCollectionPreDeletePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreUpdatePluginInterface>
     */
    protected function getOrderItemsPreUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostCreatePluginInterface>
     */
    protected function getOrderItemCollectionPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface>
     */
    protected function getOrderItemCollectionPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>
     */
    protected function getOrderItemInitialStateProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>
     */
    protected function getOrderItemInitialStateProviderPluginsForOrderAmendment(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>
     */
    protected function getOrderItemInitialStateProviderPluginsForOrderAmendmentAsync(): array
    {
        return [];
    }
}
