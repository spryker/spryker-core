<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\SalesSplit\Business\Model\Fixtures;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption as OriginalSpySalesOrderItemOption;

class SpySalesOrderItemOptionMock extends OriginalSpySalesOrderItemOption
{

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected $propelModelCopy;

    /**
     * @param bool|false $deepCopy
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function copy($deepCopy = false)
    {
        $this->propelModelCopy = parent::copy($deepCopy);

        return $this->propelModelCopy;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function getCreatedCopy()
    {
        return $this->propelModelCopy;
    }

}
