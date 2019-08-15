<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory getFactory()
 */
class ContentStorageEntityManager extends AbstractEntityManager implements ContentStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentStorageTransfer $contentStorageTransfer
     *
     * @return void
     */
    public function saveContentStorageEntity(ContentStorageTransfer $contentStorageTransfer): void
    {
        $storageEntity = $this->getFactory()
            ->createContentStorageQuery()
            ->filterByIdContentStorage($contentStorageTransfer->getIdContentStorage())
            ->findOneOrCreate();

        $storageEntity->fromArray($contentStorageTransfer->toArray());

        $storageEntity->save();
    }
}
