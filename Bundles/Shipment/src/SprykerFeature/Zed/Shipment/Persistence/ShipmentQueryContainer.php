<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Shipment\Persistence\Base\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

/**
 * @method ShipmentPersistence getFactory()
 */
class ShipmentQueryContainer extends AbstractQueryContainer implements ShipmentQueryContainerInterface
{

    /**
     * @return SpyShipmentCarrierQuery
     */
    public function queryCarriers()
    {
        return new SpyShipmentCarrierQuery();
    }

    /**
     * @return SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers()
    {
        return $this->queryCarriers()->findByIsActive(true);
    }

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryMethods()
    {
        return new SpyShipmentMethodQuery();
    }

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryActiveMethods()
    {
        return $this->queryMethods()->filterByIsActive(true);
    }

    /**
     * @param int $idMethod
     *
     * @return SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod)
    {
        $query = $this->queryMethods();
        $query->filterByIdShipmentMethod($idMethod);

        return $query;
    }

}
