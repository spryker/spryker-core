<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui;

use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToCustomerFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeBridge;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceBridge;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig getConfig()
 */
class MerchantSalesReturnMerchantUserGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_MERCHANT_SALES_ORDER = 'PROPEL_QUERY_MERCHANT_SALES_ORDER';

    public const PROPEL_QUERY_SALES_RETURN = 'PROPEL_QUERY_SALES_RETURN';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_MERCHANT_SALES_ORDER = 'FACADE_MERCHANT_SALES_ORDER';
    public const FACADE_MERCHANT_OMS = 'FACADE_MERCHANT_OMS';
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    public const FACADE_MERCHANT_SHIPMENT = 'FACADE_MERCHANT_SHIPMENT';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const SERVICE_DATE_TIME = 'SERVICE_DATE_TIME';

    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';
    public const PLUGIN_SHIPMENT_FORM_TYPE = 'PLUGIN_SHIPMENT_FORM_TYPE';
    public const PLUGIN_ITEM_FORM_TYPE = 'PLUGIN_ITEM_FORM_TYPE';
    public const FACADE_SALES_RETURN = 'FACADE_SALES_RETURN';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

//        $container = $this->addMerchantSalesOrderQuery($container);
//        $container = $this->addMoneyFacade($container);
//        $container = $this->addUtilSanitizeService($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addMerchantSalesOrderFacade($container);
        $container = $this->addDateTimeService($container);
        $container = $this->addSalesReturnPropelQuery($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addSalesReturnFacade($container);
        $container = $this->addMerchantOmsFacade($container);
//        $container = $this->addShipmentService($container);
//        $container = $this->addShipmentFacade($container);
//        $container = $this->addShipmentFormTypePlugin($container);
//        $container = $this->addItemFormTypePlugin($container);
//        $container = $this->addMerchantShipmentFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_SALES_ORDER, function (Container $container) {
            return new MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeBridge($container->getLocator()->merchantSalesOrder()->facade());
        });

        return $container;
    }

//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addMerchantSalesOrderQuery(Container $container): Container
//    {
//        $container->set(static::PROPEL_QUERY_MERCHANT_SALES_ORDER, $container->factory(function () {
//            return SpyMerchantSalesOrderQuery::create();
//        }));
//
//        return $container;
//    }
//
//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addMoneyFacade(Container $container): Container
//    {
//        $container->set(static::FACADE_MONEY, function (Container $container) {
//            return new MerchantSalesOrderMerchantUserGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
//        });
//
//        return $container;
//    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeBridge($container->getLocator()->merchantUser()->facade());
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
    protected function addSalesReturnFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_RETURN, function (Container $container) {
            return new MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeBridge($container->getLocator()->salesReturn()->facade());
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
            return new MerchantSalesReturnMerchantUserGuiToCustomerFacadeBridge($container->getLocator()->customer()->facade());
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
            return new MerchantSalesReturnMerchantUserGuiToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addUtilSanitizeService(Container $container): Container
//    {
//        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
//            return new MerchantSalesOrderMerchantUserGuiToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
//        });
//
//        return $container;
//    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_DATE_TIME, function (Container $container) {
            return new MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceBridge($container->getLocator()->utilDateTime()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_OMS, function (Container $container) {
            return new MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeBridge(
                $container->getLocator()->merchantOms()->facade()
            );
        });

        return $container;
    }

//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addShipmentService(Container $container): Container
//    {
//        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
//            return new MerchantSalesOrderMerchantUserGuiToShipmentServiceBridge($container->getLocator()->shipment()->service());
//        });
//
//        return $container;
//    }
//
//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addShipmentFacade(Container $container): Container
//    {
//        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
//            return new MerchantSalesOrderMerchantUserGuiToShipmentFacadeBridge(
//                $container->getLocator()->shipment()->facade()
//            );
//        });
//
//        return $container;
//    }
//
//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addShipmentFormTypePlugin(Container $container): Container
//    {
//        $container->set(static::PLUGIN_SHIPMENT_FORM_TYPE, function () {
//            return $this->getShipmentFormTypePlugin();
//        });
//
//        return $container;
//    }
//
//    /**
//     * @throws \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Exception\MissingShipmentFormTypePluginException
//     *
//     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
//     */
//    protected function getShipmentFormTypePlugin(): FormTypeInterface
//    {
//        throw new MissingShipmentFormTypePluginException(
//            sprintf(
//                'Missing instance of %s! You need to configure ShipmentFormType ' .
//                'in your own MerchantSalesOrderMerchantUserGuiDependencyProvider::getShipmentFormTypePlugin() ' .
//                'to be able to manage merchant order shipments.',
//                FormTypeInterface::class
//            )
//        );
//    }
//
//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addItemFormTypePlugin(Container $container): Container
//    {
//        $container->set(static::PLUGIN_ITEM_FORM_TYPE, function () {
//            return $this->getItemFormTypePlugin();
//        });
//
//        return $container;
//    }
//
//    /**
//     * @throws \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Exception\MissingItemFormTypePluginException
//     *
//     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
//     */
//    protected function getItemFormTypePlugin(): FormTypeInterface
//    {
//        throw new MissingItemFormTypePluginException(
//            sprintf(
//                'Missing instance of %s! You need to configure ItemFormType ' .
//                'in your own MerchantSalesOrderMerchantUserGuiDependencyProvider::getItemFormTypePlugin() ' .
//                'to be able to manage merchant order shipments.',
//                FormTypeInterface::class
//            )
//        );
//    }
//
//    /**
//     * @param \Spryker\Zed\Kernel\Container $container
//     *
//     * @return \Spryker\Zed\Kernel\Container
//     */
//    protected function addMerchantShipmentFacade(Container $container): Container
//    {
//        $container->set(static::FACADE_MERCHANT_SHIPMENT, function (Container $container) {
//            return new MerchantSalesOrderMerchantUserGuiToMerchantShipmentFacadeBridge(
//                $container->getLocator()->merchantShipment()->facade()
//            );
//        });
//
//        return $container;
//    }
}
