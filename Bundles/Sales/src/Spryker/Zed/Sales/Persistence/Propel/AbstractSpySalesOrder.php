<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Persistence\Propel;

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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    public function getSpyPaymentPayolution()
    {
        return $this->getSpyPaymentPayolutions()->getFirst();
    }

}
