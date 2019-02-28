<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

interface OmsRepositoryInterface
{
    /**
     * @param array $keys
     * @param array $stateBlackList
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getMatrixOrderItems(array $keys, array $stateBlackList): array;
}
