<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui;

use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToOmsFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeBridge;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceBridge;

/**
 * @method \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig getConfig()
 */
class SalesReturnGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES_RETURN = 'FACADE_SALES_RETURN';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_OMS = 'FACADE_OMS';

    public const PLUGINS_RETURN_CREATE_FORM_HANDLER = 'PLUGINS_RETURN_CREATE_FORM_HANDLER';
    public const PLUGINS_RETURN_CREATE_TEMPLATE = 'PLUGINS_RETURN_CREATE_TEMPLATE';

    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const PROPEL_QUERY_SALES_RETURN = 'PROPEL_QUERY_SALES_RETURN';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addSalesReturnFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addSalesReturnPropelQuery($container);

        $container = $this->addReturnCreateFormHandlerPlugins($container);
        $container = $this->addReturnCreateTemplatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesReturnFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_RETURN, function (Container $container) {
            return new SalesReturnGuiToSalesReturnFacadeBridge($container->getLocator()->salesReturn()->facade());
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
            return new SalesReturnGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new SalesReturnGuiToCustomerFacadeBridge(
                $container->getLocator()->customer()->facade()
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
            return new SalesReturnGuiToOmsFacadeBridge(
                $container->getLocator()->oms()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesReturnGuiToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new SalesReturnGuiToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new SalesReturnGuiToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesReturnPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_RETURN, $container->factory(function () {
            return SpySalesReturnQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReturnCreateFormHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RETURN_CREATE_FORM_HANDLER, function () {
            return $this->getReturnCreateFormHandlerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReturnCreateTemplatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RETURN_CREATE_TEMPLATE, function () {
            return $this->getReturnCreateTemplatePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface[]
     */
    protected function getReturnCreateFormHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateTemplatePluginInterface[]
     */
    protected function getReturnCreateTemplatePlugins(): array
    {
        return [];
    }
}
