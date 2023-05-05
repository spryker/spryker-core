<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ServicePointSearchRepositoryInterface
{
    /**
     * @param list<int> $servicePointIds
     *
     * @return array<\Generated\Shared\Transfer\ServicePointSearchTransfer>
     */
    public function getServicePointSearchTransfersByServicePointIds(array $servicePointIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $servicePointIds = []): array;
}
