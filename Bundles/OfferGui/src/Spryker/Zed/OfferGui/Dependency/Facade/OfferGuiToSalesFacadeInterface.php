<?php


namespace Spryker\Zed\OfferGui\Dependency\Facade;


interface OfferGuiToSalesFacadeInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);
}