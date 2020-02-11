<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentGui\Communication\Exception\MissingMoneyCollectionFormTypePluginException;
use Spryker\Zed\ShipmentGui\Communication\Exception\MissingStoreRelationFormTypePluginException;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeBridge;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeBridge;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeBridge;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeBridge;
use Spryker\Zed\ShipmentGui\Dependency\Service\ShipmentGuiToShipmentServiceBridge;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_TAX = 'FACADE_TAX';

    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    public const PROPEL_QUERY_SHIPMENT_METHOD = 'PROPEL_QUERY_SHIPMENT_METHOD';

    public const PLUGIN_MONEY_COLLECTION_FORM_TYPE = 'PLUGIN_MONEY_COLLECTION_FORM_TYPE';
    public const PLUGIN_STORE_RELATION_FORM_TYPE = 'PLUGIN_STORE_RELATION_FORM_TYPE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSalesFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addTaxFacade($container);
        $container = $this->addShipmentService($container);
        $container = $this->addShipmentMethodQuery($container);
        $container = $this->addMoneyCollectionFormTypePlugin($container);
        $container = $this->addStoreRelationFormTypePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container): Container
    {
        $container->set(static::FACADE_TAX, function (Container $container) {
            return new ShipmentGuiToTaxFacadeBridge(
                $container->getLocator()->tax()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreRelationFormTypePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return $this->getStoreRelationFormTypePlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Zed\ShipmentGui\Communication\Exception\MissingStoreRelationFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin()
    {
        throw new MissingStoreRelationFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure StoreRelationFormType ' .
                'in your own ShipmentGuiDependencyProvider::getStoreRelationFormTypePlugin() ' .
                'to be able to manage shipment methods.',
                FormTypeInterface::class
            )
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyCollectionFormTypePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY_COLLECTION_FORM_TYPE, function (Container $container) {
            return $this->getMoneyCollectionFormTypePlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Zed\ShipmentGui\Communication\Exception\MissingMoneyCollectionFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getMoneyCollectionFormTypePlugin(Container $container)
    {
        throw new MissingMoneyCollectionFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure MoneyCollectionFormType ' .
                'in your own ShipmentGuiDependencyProvider::getMoneyCollectionFormTypePlugin() ' .
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
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new ShipmentGuiToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new ShipmentGuiToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
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
            return new ShipmentGuiToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new ShipmentGuiToShipmentServiceBridge($container->getLocator()->shipment()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentMethodQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SHIPMENT_METHOD, function () {
            return SpyShipmentMethodQuery::create();
        });

        return $container;
    }
}
