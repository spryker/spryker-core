<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence\Propel;

use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrder as BaseSpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderTableMap;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpySalesOrder extends BaseSpySalesOrder
{

    /**
     * Set the value of [increment_id] column.
     *
     * @param string $v new value
     * @deprecated
     * @return $this|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder The current object (for fluent API support)
     */
    public function setIncrementId($v)
    {
        if ($v !== null) {
            $v = (string)$v;
        }

        if ($this->order_reference !== $v) {
            $this->order_reference = $v;
            $this->modifiedColumns[SpySalesOrderTableMap::COL_ORDER_REFERENCE] = true;
        }

        return $this;
    }

    /**
     * Get the [increment_id] column value.
     *
     * @deprecated
     * @return string
     */
    public function getIncrementId()
    {
        return $this->order_reference;
    }
}
