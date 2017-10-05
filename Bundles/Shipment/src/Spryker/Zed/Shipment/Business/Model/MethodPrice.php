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
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return void
     */
    public function save(ShipmentMethodTransfer $methodTransfer)
    {
        foreach ($methodTransfer->getPrices() as $moneyValueTransfer) {
            $shipmentMethodPriceEntity = $this->queryContainer
                ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                    $methodTransfer->getIdShipmentMethod(),
                    $moneyValueTransfer->getFkStore(),
                    $moneyValueTransfer->getFkCurrency()
                )
                ->findOneOrCreate();

            $shipmentMethodPriceEntity->setGrossAmount($moneyValueTransfer->getGrossAmount());
            $shipmentMethodPriceEntity->setNetAmount($moneyValueTransfer->getNetAmount());
            $shipmentMethodPriceEntity->save();
        }
    }

}
