<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper\ShipmentMethodPricesMapper;
use Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper\ShipmentMethodPricesMapperInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class ShipmentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function createShipmentCarrierQuery()
    {
        return SpyShipmentCarrierQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function createShipmentMethodQuery()
    {
        return SpyShipmentMethodQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function createSalesShipmentQuery()
    {
        return SpySalesShipmentQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function createShipmentMethodPriceQuery()
    {
        return SpyShipmentMethodPriceQuery::create();
    }

    /**
     * @return \Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper\ShipmentMethodPricesMapperInterface
     */
    public function createShipmentMethodPricesMapper(): ShipmentMethodPricesMapperInterface
    {
        return new ShipmentMethodPricesMapper($this->getCurrencyFacade(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    public function getCurrencyFacade(): ShipmentToCurrencyInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    public function getStoreFacade(): ShipmentToStoreInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_STORE);
    }
}
