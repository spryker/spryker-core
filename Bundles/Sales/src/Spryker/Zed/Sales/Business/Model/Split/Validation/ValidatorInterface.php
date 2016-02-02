<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model\Split\Validation;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface ValidatorInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
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
