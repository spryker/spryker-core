<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use ArrayObject;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory getFactory()
 */
interface ContentStorageRepositoryInterface
{
    /**
     * @param int[] $contentIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findContentStorageByContentIds(array $contentIds): ArrayObject;

    /**
     * @param array $contentIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findContentByIds(array $contentIds): ArrayObject;

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findAllContentStorage(): ArrayObject;

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findAllContent(): ArrayObject;
}
