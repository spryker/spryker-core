<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


interface ShipmentDiscountConnectorToMoneyInterface
{

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);

}