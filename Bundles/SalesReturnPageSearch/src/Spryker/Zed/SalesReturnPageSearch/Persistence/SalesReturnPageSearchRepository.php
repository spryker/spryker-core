<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchPersistenceFactory getFactory()
 */
class SalesReturnPageSearchRepository extends AbstractRepository implements SalesReturnPageSearchRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findReturnReasonSearchDataTransferByIds(int $offset, int $limit, array $ids): array
    {
        // TODO: Implement findReturnReasonSearchDataTransferByIds() method.
    }
}
