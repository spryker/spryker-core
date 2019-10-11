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
     * @param int[] $contentIds
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findContentStorageByContentIds(array $contentIds): array;

    /**
     * @param array $contentIds
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findContentByIds(array $contentIds): array;

    /**
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findAllContentStorage(): array;

    /**
     * @deprecated Use getContentByFilter instead.
     *
     * @param array $contentIds
     *
     * @return \Generated\Shared\Transfer\SpyContentEntityTransfer[]
     */
    public function findContentByContentIds(array $contentIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyContentEntityTransfer[]
     */
    public function getContentByFilter(FilterTransfer $filterTransfer): array;
}
