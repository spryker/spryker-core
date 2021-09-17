<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface SalesReturnSearchRepositoryInterface
{
    /**
     * @param array<int> $returnReasonIds
     *
     * @return array<\Generated\Shared\Transfer\ReturnReasonSearchTransfer>
     */
    public function getReturnReasonSearchTransfersByReturnReasonIds(array $returnReasonIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $returnReasonIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getReturnReasonSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $returnReasonIds = []): array;
}
