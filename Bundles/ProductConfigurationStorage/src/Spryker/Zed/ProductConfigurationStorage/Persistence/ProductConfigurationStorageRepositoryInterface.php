<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

interface ProductConfigurationStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductConfigurationStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer[]
     */
    public function findProductConfigurationStorageTransfersByProductConfigurationIds(array $ids): array;
}
