<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory getFactory()
 */
interface ContentStorageRepositoryInterface
{
    /**
     * @param array<int> $contentIds
     *
     * @return array<\Generated\Shared\Transfer\ContentStorageTransfer>
     */
    public function findContentStorageByContentIds(array $contentIds): array;

    /**
     * @param array $contentIds
     *
     * @return array<\Generated\Shared\Transfer\ContentTransfer>
     */
    public function findContentByIds(array $contentIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ContentStorageTransfer>
     */
    public function findAllContentStorage(): array;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param array $contentIds
     *
     * @return array<\Generated\Shared\Transfer\SpyContentEntityTransfer>
     */
    public function findContentByContentIds(array $contentIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ContentTransfer>
     */
    public function getContentTransfersByFilter(FilterTransfer $filterTransfer): array;
}
