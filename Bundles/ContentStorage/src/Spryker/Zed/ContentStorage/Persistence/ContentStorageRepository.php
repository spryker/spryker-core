<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory getFactory()
 */
class ContentStorageRepository extends AbstractRepository implements ContentStorageRepositoryInterface
{
    /**
     * @param int[] $contentIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findContentStorageByContentIds(array $contentIds): ArrayObject
    {
        $contentStorageTransfers = new ArrayObject();
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->filterByFkContent($contentIds, Criteria::IN)
            ->find();

        foreach ($contentStorageEntities as $contentStorageEntity) {
            $contentStorageTransfer = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentStorageEntityToTransfer($contentStorageEntity, new ContentStorageTransfer());
            $contentStorageTransfers->append($contentStorageTransfer);
        }

        return $contentStorageTransfers;
    }

    /**
     * @param array $contentIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findContentByIds(array $contentIds): ArrayObject
    {
        $contentTransfers = new ArrayObject();
        $contentEntities = $this->getFactory()
            ->getContentQuery()
            ->filterByIdContent($contentIds, Criteria::IN)
            ->joinWithSpyContentLocalized()
            ->find();

        foreach ($contentEntities as $contentEntity) {
            $contentStorageTransfer = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentEntityToTransfer($contentEntity, new ContentTransfer());
            $contentTransfers->append($contentStorageTransfer);
        }

        return $contentTransfers;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findAllContentStorage(): ArrayObject
    {
        $contentStorageTransfers = new ArrayObject();
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->find();

        foreach ($contentStorageEntities as $contentStorageEntity) {
            $contentStorageTransfer = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentStorageEntityToTransfer($contentStorageEntity, new ContentStorageTransfer());
            $contentStorageTransfers->append($contentStorageTransfer);
        }

        return $contentStorageTransfers;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findAllContent(): ArrayObject
    {
        $contentTransfers = new ArrayObject();
        $contentEntities = $this->getFactory()
            ->getContentQuery()
            ->useSpyContentLocalizedQuery()
                ->joinSpyLocale()
            ->endUse()
            ->find();

        foreach ($contentEntities as $contentEntity) {
            $contentStorageTransfer = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentEntityToTransfer($contentEntity, new ContentTransfer());
            $contentTransfers->append($contentStorageTransfer);
        }

        return $contentTransfers;
    }
}
