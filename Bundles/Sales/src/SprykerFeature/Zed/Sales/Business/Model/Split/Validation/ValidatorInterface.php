<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split\Validation;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

interface ValidatorInterface
{

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param int $quantityToSplit
     *
     * @return bool
     */
    public function isValid(SpySalesOrderItem $salesOrderItem, $quantityToSplit);

    /**
     * @return array
     */
    public function getMessages();

}
