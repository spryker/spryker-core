<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrix\Dependency\Facade;

use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;

interface OrderMatrixToOmsFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function getOrderMatrixCollection(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): OrderMatrixCollectionTransfer;

    /**
     * @return array<int, string>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(): array;
}
