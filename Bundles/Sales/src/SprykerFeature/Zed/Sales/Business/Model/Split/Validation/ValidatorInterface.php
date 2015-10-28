<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split\Validation;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

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
