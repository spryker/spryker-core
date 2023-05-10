<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Persistence;

interface ServicePointStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointStorageSynchronizationDataTransfers(int $offset, int $limit, array $servicePointIds = []): array;
}
