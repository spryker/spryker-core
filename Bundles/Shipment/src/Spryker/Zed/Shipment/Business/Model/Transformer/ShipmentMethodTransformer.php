<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentMethodTransformer implements ShipmentMethodTransformerInterface
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var array
     */
    protected $currencyCache = [];

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentToCurrencyInterface $currencyFacade, ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->currencyFacade = $currencyFacade;
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function transformShipmentMethodEntityToShipmentMethodTransfer(SpyShipmentMethod $shipmentMethodEntity)
    {
            $shipmentMethodTransfer = (new ShipmentMethodTransfer())
                ->fromArray($shipmentMethodEntity->toArray(), true)
                ->setPrices($this->getPriceCollection($shipmentMethodEntity));

            return $shipmentMethodTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $methodEntity
     *
     * @return \ArrayObject
     */
    protected function getPriceCollection(SpyShipmentMethod $methodEntity)
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($methodEntity->getShipmentMethodPrices() as $shipmentMethodPriceEntity) {
            $moneyValueCollection->append(
                (new MoneyValueTransfer())
                    ->fromArray($shipmentMethodPriceEntity->toArray(), true)
                    ->setIdEntity($shipmentMethodPriceEntity->getIdShipmentMethodPrice())
                    ->setCurrency(
                        $this->hydrateStoreEntityIntoCurrencyTransfer(
                            $this->getCurrencyTransfer($shipmentMethodPriceEntity->getFkCurrency()),
                            $shipmentMethodPriceEntity->getStore()
                        )
                    )
            );
        }

        return $moneyValueCollection;
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer($idCurrency)
    {
        if (array_key_exists($idCurrency, $this->currencyCache)) {
            return (new CurrencyTransfer())->fromArray($this->currencyCache[$idCurrency]);
        }

        $currencyTransfer = $this->currencyFacade->getByIdCurrency($idCurrency);
        $this->currencyCache[$idCurrency] = $currencyTransfer->toArray();

        return $currencyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function hydrateStoreEntityIntoCurrencyTransfer(CurrencyTransfer $currencyTransfer, SpyStore $storeEntity)
    {
        $currencyTransfer->setStore(
            (new StoreTransfer())->fromArray($storeEntity->toArray(), true)
        );

        return $currencyTransfer;
    }

}
