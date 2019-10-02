<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockRepository extends AbstractRepository implements CmsBlockRepositoryInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById(int $idCmsBlock): ?CmsBlockTransfer
    {
        $cmsBlockEntity = $this->getFactory()->createCmsBlockQuery()->findOneByIdCmsBlock($idCmsBlock);

        if (!$cmsBlockEntity) {
            return null;
        }

        return $this->getFactory()->createCmsBlockMapper()->mapCmsBlockEntityToTransfer($cmsBlockEntity);
    }

    /**
     * @return int
     */
    public function findMaxIdCmsBlock(): int
    {
        $maxIdCmsBlock = $this->getFactory()->createCmsBlockQuery()
            ->select(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK)
            ->orderByIdCmsBlock(Criteria::DESC)
            ->findOne();

        return $maxIdCmsBlock ?: 0;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getFactory()
            ->createCmsBlockQuery()
            ->filterByKey($key)
            ->exists();
    }
}
