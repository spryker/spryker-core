<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;

class ShipmentMethodExtender implements ShipmentMethodExtenderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface
     */
    protected $methodTransformer;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface
     */
    protected $shipmentFetcher;

    /**
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $methodTransformer
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface $shipmentFetcher
     */
    public function __construct(ShipmentMethodTransformerInterface $methodTransformer, ShipmentFetcherInterface $shipmentFetcher)
    {
        $this->methodTransformer = $methodTransformer;
        $this->shipmentFetcher = $shipmentFetcher;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function extendShipmentMethodTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer): ShipmentMethodTransfer
    {
        $methodEntity = $this->shipmentFetcher
            ->findActiveShipmentMethodWithPricesAndCarrierById($shipmentMethodTransfer->getIdShipmentMethod());

        if ($methodEntity === null) {
            return $shipmentMethodTransfer;
        }

        $methodPriceEntity = $this->shipmentFetcher
            ->findMethodPriceByShipmentMethodAndCurrentStoreCurrency($methodEntity, $orderTransfer->getCurrencyIsoCode());

        if ($methodPriceEntity === null) {
            return $shipmentMethodTransfer;
        }

        $price = $orderTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $methodPriceEntity->getDefaultGrossPrice() :
            $methodPriceEntity->getDefaultNetPrice();

        $shipmentMethodTransfer = $this->methodTransformer->transformEntityToTransfer($methodEntity);
        $shipmentMethodTransfer
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setStoreCurrencyPrice($price);

        return $shipmentMethodTransfer;
    }
}
