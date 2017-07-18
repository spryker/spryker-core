<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


interface ShipmentDiscountReaderInterface
{
    /**
     * @return array
     */
    public function getCarrierList();

}