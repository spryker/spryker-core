<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\QueryContainer\ProductOptionToCountryQueryContainerBridge;
use Spryker\Zed\ProductOption\Dependency\QueryContainer\ProductOptionToSalesQueryContainerBridge;
use Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToUtilEncodingServiceBridge;
use Spryker\Zed\ProductOption\Exception\MissingMoneyCollectionFormTypePluginException;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 */
class ProductOptionDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_TAX = 'FACADE_TAX';

    /**
     * @var string
     */
    public const FACADE_TOUCH = 'FACADE_TOUCH';

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
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_PRICE = 'FACADE_PRICE';

    /**
     * @var string
     */
    public const FACADE_EVENT = 'FACADE_EVENT';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const MONEY_COLLECTION_FORM_TYPE_PLUGIN = 'MONEY_COLLECTION_FORM_TYPE_PLUGIN';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_COUNTRY = 'QUERY_CONTAINER_COUNTRY';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OPTION_VALUES_PRE_REMOVE = 'PLUGINS_PRODUCT_OPTION_VALUES_PRE_REMOVE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OPTION_LIST_ACTION_VIEW_DATA_EXPANDER = 'PLUGINS_PRODUCT_OPTION_LIST_ACTION_VIEW_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OPTION_LIST_TABLE_QUERY_CRITERIA_EXPANDER = 'PLUGINS_PRODUCT_OPTION_LIST_TABLE_QUERY_CRITERIA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OPTION_GROUP_EXPANDER = 'PLUGINS_PRODUCT_OPTION_GROUP_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductOptionToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new ProductOptionToTouchFacadeBridge($container->getLocator()->touch()->facade());
        });

        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new ProductOptionToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

        $container->set(static::FACADE_TAX, function (Container $container) {
            return new ProductOptionToTaxFacadeBridge($container->getLocator()->tax()->facade());
        });

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addProductOptionValuesPreRemovePlugins($container);
        $container = $this->addProductOptionGroupExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new ProductOptionToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductOptionToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ProductOptionToPriceFacadeBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container)
    {
        $container->set(static::FACADE_EVENT, function (Container $container) {
            return new ProductOptionToEventFacadeBridge($container->getLocator()->event()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_SALES, function (Container $container) {
            return new ProductOptionToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_COUNTRY, function (Container $container) {
            return new ProductOptionToCountryQueryContainerBridge($container->getLocator()->country()->queryContainer());
        });

        $container = $this->addProductOptionListTableQueryCriteriaExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyCollectionFormTypePlugin(Container $container)
    {
        $container->set(static::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function (Container $container) {
            return $this->createMoneyCollectionFormTypePlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Zed\ProductOption\Exception\MissingMoneyCollectionFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function createMoneyCollectionFormTypePlugin(Container $container)
    {
        throw new MissingMoneyCollectionFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure MoneyCollectionFormType ' .
                'in your own ProductOptionDependencyProvider::createMoneyCollectionFormTypePlugin() ' .
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
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_TAX, function (Container $container) {
            return new ProductOptionToTaxFacadeBridge($container->getLocator()->tax()->facade());
        });

        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductOptionToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new ProductOptionToMoneyFacadeBridge($container->getLocator()->money()->facade());
        });

        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new ProductOptionToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductOptionToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyCollectionFormTypePlugin($container);
        $container = $this->addProductOptionListActionViewDataExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionValuesPreRemovePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OPTION_VALUES_PRE_REMOVE, function (Container $container) {
            return $this->getProductOptionValuesPreRemovePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface>
     */
    protected function getProductOptionValuesPreRemovePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionListActionViewDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OPTION_LIST_ACTION_VIEW_DATA_EXPANDER, function (Container $container) {
            return $this->getProductOptionListActionViewDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListActionViewDataExpanderPluginInterface>
     */
    protected function getProductOptionListActionViewDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionListTableQueryCriteriaExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OPTION_LIST_TABLE_QUERY_CRITERIA_EXPANDER, function (Container $container) {
            return $this->getProductOptionListTableQueryCriteriaExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListTableQueryCriteriaExpanderPluginInterface>
     */
    protected function getProductOptionListTableQueryCriteriaExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionGroupExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OPTION_GROUP_EXPANDER, function (Container $container) {
            return $this->getProductOptionGroupExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface>
     */
    protected function getProductOptionGroupExpanderPlugins(): array
    {
        return [];
    }
}
