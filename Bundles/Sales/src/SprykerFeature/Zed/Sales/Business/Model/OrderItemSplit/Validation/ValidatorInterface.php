<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use SprykerFeature\Zed\Sales\Persistence;

interface ValidatorInterface
{
    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer                              $quantityToSplit
     *
     * @return bool
     */
    public function isValid(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantityToSplit);

    /**
     * @return array
     */
    public function getMessages();
}
