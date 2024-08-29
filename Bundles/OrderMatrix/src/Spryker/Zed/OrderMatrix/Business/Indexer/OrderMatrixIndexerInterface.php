<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrix\Business\Indexer;

use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;

interface OrderMatrixIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
     * @param array<string, array<string, array<int>>> $orderMatrix
     *
     * @return array<string, array<string, array<int>>>
     */
    public function getOrderMatrixIndexedByStateProcessAndDateRange(OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer, array $orderMatrix): array;
}
