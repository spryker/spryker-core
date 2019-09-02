<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

interface OmsRepositoryInterface
{
    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array;

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getSalesOrderItemsByIdSalesOrder(int $idOrder): array;
}
