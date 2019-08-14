<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockRepository extends AbstractRepository implements CmsBlockRepositoryInterface
{
    protected const COL_MAX_ID_CMS_BLOCK = 'max_id_cms_block';

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

        $cmsBlockTransfer = $this->getFactory()->createCmsBlockMapper()->mapCmsBlockEntityToTransfer($cmsBlockEntity);

        return $cmsBlockTransfer;
    }

    /**
     * @return int
     */
    public function findMaxIdCmsBlock(): int
    {
        $clause = 'MAX(' . SpyCmsBlockTableMap::COL_ID_CMS_BLOCK . ')';
        $maxIdCmsBlock = $this->getFactory()->createCmsBlockQuery()
            ->select(static::COL_MAX_ID_CMS_BLOCK)
            ->addAsColumn(static::COL_MAX_ID_CMS_BLOCK, $clause)
            ->findOne();

        if (!$maxIdCmsBlock) {
            return 0;
        }

        return $maxIdCmsBlock;
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
