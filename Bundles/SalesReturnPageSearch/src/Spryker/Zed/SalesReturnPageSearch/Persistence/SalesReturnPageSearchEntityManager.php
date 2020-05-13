<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchPersistenceFactory getFactory()
 */
class SalesReturnPageSearchEntityManager extends AbstractEntityManager implements SalesReturnPageSearchEntityManagerInterface
{
    /**
     * @param int[] $returnReasonsIds
     *
     * @return void
     */
    public function deleteReturnReasonSearchByReturnReasonIds(array $returnReasonsIds): void
    {
        if (!$returnReasonsIds) {
            return;
        }

        $this->getFactory()
            ->getSalesReturnReasonPageSearchPropelQuery()
            ->filterByFkSalesReturnReason_In($returnReasonsIds)
            ->find()
            ->delete();
    }
}
