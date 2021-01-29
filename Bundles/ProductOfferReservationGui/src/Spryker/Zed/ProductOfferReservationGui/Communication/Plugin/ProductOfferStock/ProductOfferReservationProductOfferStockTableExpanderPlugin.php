<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferReservationGui\Communication\Plugin\ProductOfferStock;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferStockGuiExtension\Dependeency\Plugin\ProductOfferStockTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferReservationGui\ProductOfferReservationGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferReservationGui\Communication\ProductOfferReservationGuiCommunicationFactory getFactory()
 */
class ProductOfferReservationProductOfferStockTableExpanderPlugin extends AbstractPlugin implements ProductOfferStockTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns reserved offers header to expand product offer stock table.
     *
     * @api
     *
     * @return string
     */
    public function getHeader(): string
    {
        return 'Reserved Offers';
    }

    /**
     * {@inheritDoc}
     * - Returns reserved offers data to expand product offer stock table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    public function getColumnData(ProductOfferStockTransfer $productOfferStockTransfer, StoreTransfer $storeTransfer): string
    {
        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferStockTransfer->getProductOfferReference())
            ->setStore($storeTransfer);

        return $this->getFactory()
            ->getOmsProductOfferReservationFacade()
            ->getQuantity($omsProductOfferReservationCriteriaTransfer)
            ->getReservationQuantity();
    }
}
