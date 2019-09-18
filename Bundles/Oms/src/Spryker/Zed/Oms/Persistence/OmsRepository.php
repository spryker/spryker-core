<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array
    {
        $orderItemsMatrixResult = $this->getFactory()->getOmsQueryContainer()
            ->queryGroupedMatrixOrderItems($processIds, $stateBlackList)
            ->find();

        return $this->getFactory()
            ->createOrderItemMatrixMapper()
            ->mapOrderItemMatrix($orderItemsMatrixResult->getArrayCopy());
    }
}
