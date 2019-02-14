<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;
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
     * @var \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface
     */
    protected $shipmentMethodTransformer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $shipmentMethodTransformer
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        ShipmentToStoreInterface $storeFacade,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentMethodTransformerInterface $shipmentMethodTransformer
    ) {
        $this->queryContainer = $queryContainer;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
        $this->shipmentMethodTransformer = $shipmentMethodTransformer;
    }

    /**
     * @param int $shipmentMethodId
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findActiveShipmentMethodWithPricesAndCarrierById(int $shipmentMethodId): ?ShipmentMethodTransfer
    {
        /**
         * @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null $shipmentMethodEntity|null
         */
        $shipmentMethodEntity = $this->queryContainer
            ->queryActiveMethodsWithMethodPricesAndCarrierById($shipmentMethodId)
            ->find()
            ->getFirst();

        if ($shipmentMethodEntity === null) {
            return null;
        }

        return $this->shipmentMethodTransformer->transformEntityToTransfer($shipmentMethodEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\ShipmentPriceTransfer|null
     */
    public function findMethodPriceByShipmentMethodAndCurrentStoreCurrency(ShipmentMethodTransfer $shipmentMethodTransfer, string $currencyIsoCode): ?ShipmentPriceTransfer
    {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();

        $idCurrenyIsoCode = $this->currencyFacade
            ->fromIsoCode($currencyIsoCode)
            ->getIdCurrency();

        /**
         * @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice|null $shipmentMethodPriceEntity
         */
        $shipmentMethodPriceEntity = $this->queryContainer
            ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                $shipmentMethodTransfer->getIdShipmentMethod(),
                $idStore,
                $idCurrenyIsoCode
            )
            ->findOne();

        if ($shipmentMethodPriceEntity === null) {
            return null;
        }

        return (new ShipmentPriceTransfer())
            ->fromArray($shipmentMethodPriceEntity->toArray(), true);
    }
}
