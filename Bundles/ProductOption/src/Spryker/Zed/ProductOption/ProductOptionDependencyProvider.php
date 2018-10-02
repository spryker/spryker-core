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

class ProductOptionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_TAX = 'FACADE_TAX';
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_EVENT = 'FACADE_EVENT';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    public const MONEY_COLLECTION_FORM_TYPE_PLUGIN = 'MONEY_COLLECTION_FORM_TYPE_PLUGIN';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';
    public const QUERY_CONTAINER_COUNTRY = 'QUERY_CONTAINER_COUNTRY';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductOptionToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductOptionToTouchFacadeBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductOptionToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductOptionToTaxFacadeBridge($container->getLocator()->tax()->facade());
        };

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addEventFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new ProductOptionToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ProductOptionToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container[static::FACADE_PRICE] = function (Container $container) {
            return new ProductOptionToPriceFacadeBridge($container->getLocator()->price()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container)
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new ProductOptionToEventFacadeBridge($container->getLocator()->event()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new ProductOptionToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_COUNTRY] = function (Container $container) {
            return new ProductOptionToCountryQueryContainerBridge($container->getLocator()->country()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyCollectionFormTypePlugin(Container $container)
    {
        $container[static::MONEY_COLLECTION_FORM_TYPE_PLUGIN] = function (Container $container) {
            return $this->createMoneyCollectionFormTypePlugin($container);
        };

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
                FormTypeInterface::class
            )
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductOptionToTaxFacadeBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductOptionToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_MONEY] = function (Container $container) {
            return new ProductOptionToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductOptionToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductOptionToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyCollectionFormTypePlugin($container);

        return $container;
    }
}
