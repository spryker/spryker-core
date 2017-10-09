<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class MethodPrice implements MethodPriceInterface
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return void
     */
    public function save(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            $shipmentMethodPriceEntity = $this->queryContainer
                ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                    $shipmentMethodTransfer->getIdShipmentMethod(),
                    $moneyValueTransfer->getFkStore(),
                    $moneyValueTransfer->getFkCurrency()
                )
                ->findOneOrCreate();

            $shipmentMethodPriceEntity->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
            $shipmentMethodPriceEntity->setDefaultNetPrice($moneyValueTransfer->getNetAmount());
            $shipmentMethodPriceEntity->save();
        }
    }

}
