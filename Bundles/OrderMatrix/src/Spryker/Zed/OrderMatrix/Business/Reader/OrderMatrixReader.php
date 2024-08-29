<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business\Reader;

use Generated\Shared\Transfer\OrderMatrixConditionsTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\OrderMatrix\Dependency\Facade\OrderMatrixToOmsFacadeInterface;
use Spryker\Zed\OrderMatrix\OrderMatrixConfig;

class OrderMatrixReader implements OrderMatrixReaderInterface
{
    /**
     * @param \Spryker\Zed\OrderMatrix\OrderMatrixConfig $orderMatrixConfig
     * @param \Spryker\Zed\OrderMatrix\Dependency\Facade\OrderMatrixToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        protected OrderMatrixConfig $orderMatrixConfig,
        protected OrderMatrixToOmsFacadeInterface $omsFacade
    ) {
    }

    /**
     * @return iterable<\Generated\Shared\Transfer\OrderMatrixCollectionTransfer>
     */
    public function getOrderMatrix(): iterable
    {
        $limit = $this->orderMatrixConfig->getOrderMatrixBatchSize();
        $processes = $this->omsFacade->getProcessNamesIndexedByIdOmsOrderProcess();
        $orderMatrixCriteriaTransfer = new OrderMatrixCriteriaTransfer();
        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setLimit($limit);
        $orderMatrixConditionTransfer = (new OrderMatrixConditionsTransfer())
            ->setProcessIds(array_keys($processes));
        $orderMatrixCriteriaTransfer->setOrderMatrixConditions($orderMatrixConditionTransfer);
        $offset = 0;
        do {
            $paginationTransfer->setOffset($offset);
            $orderMatrixCriteriaTransfer->setPagination($paginationTransfer);
            $orderMatrixCollectionTransfer = $this->omsFacade->getOrderMatrixCollection($orderMatrixCriteriaTransfer);
            $offset += $limit;

            yield $orderMatrixCollectionTransfer;
        } while ($orderMatrixCollectionTransfer->getOrderMatrices()->count() > 0);
    }
}
