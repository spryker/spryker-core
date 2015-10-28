<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence\Propel;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrder as BaseSpySalesOrder;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpySalesOrder extends BaseSpySalesOrder
{

    /**
     * Set the value of [increment_id] column.
     *
     * @param string $v new value
     *
     * @deprecated
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder The current object (for fluent API support)
     */
    public function setIncrementId($v)
    {
        $this->setOrderReference($v);
    }

    /**
     * Get the [increment_id] column value.
     *
     * @deprecated
     *
     * @return string
     */
    public function getIncrementId()
    {
        return $this->getOrderReference();
    }

}
