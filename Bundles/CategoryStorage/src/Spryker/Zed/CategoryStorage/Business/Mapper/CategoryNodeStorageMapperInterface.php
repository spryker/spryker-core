<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Mapper;

interface CategoryNodeStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function mapNodeTransfersToCategoryNodeStorageTransfersByLocaleAndStore(array $nodeTransfers, string $localeName, string $storeName): array;
}
