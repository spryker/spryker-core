<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split\Validation;

use SprykerFeature\Zed\Sales\Persistence;

interface ValidatorInterface
{

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param int                              $quantityToSplit
     *
     * @return bool
     */
    public function isValid(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantityToSplit);

    /**
     * @return array
     */
    public function getMessages();

}
