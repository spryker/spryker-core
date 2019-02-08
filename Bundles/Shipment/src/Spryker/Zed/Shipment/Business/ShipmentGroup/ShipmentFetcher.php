<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentFetcher implements ShipmentFetcherInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer, ShipmentToStoreInterface $storeFacade, ShipmentToCurrencyInterface $currencyFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param int $shipmentMethodId
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null
     */
    public function findActiveShipmentMethodWithPricesAndCarrierById(int $shipmentMethodId): ?SpyShipmentMethod
    {
        return $this->queryContainer
            ->queryActiveMethodsWithMethodPricesAndCarrierById($shipmentMethodId)
            ->find()
            ->getFirst();
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param string $currencyIsoCode
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice|null
     */
    public function findMethodPriceByShipmentMethodAndCurrentStoreCurrency(SpyShipmentMethod $shipmentMethodEntity, string $currencyIsoCode): ?SpyShipmentMethodPrice
    {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();

        $idCurrenyIsoCode = $this->currencyFacade
            ->fromIsoCode($currencyIsoCode)
            ->getIdCurrency();

        return $this->queryContainer
            ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                $shipmentMethodEntity->getIdShipmentMethod(),
                $idStore,
                $idCurrenyIsoCode
            )
            ->findOne();
    }
}
