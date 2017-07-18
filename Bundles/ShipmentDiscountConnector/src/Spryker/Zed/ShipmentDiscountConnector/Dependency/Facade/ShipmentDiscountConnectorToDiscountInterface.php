<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


use Generated\Shared\Transfer\ClauseTransfer;

interface ShipmentDiscountConnectorToDiscountInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $compareWith
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function queryStringCompare(ClauseTransfer $clauseTransfer, $compareWith);

}