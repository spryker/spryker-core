<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface SalesReturnPageSearchRepositoryInterface
{
    /**
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[]
     */
    public function getReturnReasonPageSearchTransfersByReturnReasonIds(array $returnReasonIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getReturnReasonSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $returnReasonIds = []): array;
}
