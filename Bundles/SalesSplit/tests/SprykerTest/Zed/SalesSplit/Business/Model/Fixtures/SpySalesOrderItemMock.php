<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesSplit\Business\Model\Fixtures;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem as OriginalSpySalesOrderItem;

class SpySalesOrderItemMock extends OriginalSpySalesOrderItem
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected $propelModelCopy;

    /**
     * @param bool $deepCopy
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function copy($deepCopy = false): OriginalSpySalesOrderItem
    {
        $this->propelModelCopy = parent::copy($deepCopy);

        return $this->propelModelCopy;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function getCreatedCopy(): OriginalSpySalesOrderItem
    {
        return $this->propelModelCopy;
    }
}
