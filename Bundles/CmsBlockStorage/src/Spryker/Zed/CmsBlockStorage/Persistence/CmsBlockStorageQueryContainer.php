<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Persistence;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStoragePersistenceFactory getFactory()
 */
class CmsBlockStorageQueryContainer extends AbstractQueryContainer implements CmsBlockStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery
     */
    public function queryCmsBlockStorageEntities(array $cmsBlockIds): SpyCmsBlockStorageQuery
    {
        return $this->getFactory()
            ->createSpyCmsBlockStorage()
            ->filterByFkCmsBlock_In($cmsBlockIds);
    }

    /**
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockWithRelationsByIds(array $cmsBlockIds): SpyCmsBlockQuery
    {
        /** @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery $query */
        $query = $this->getFactory()->createCmsBlockQuery()
            ->filterByIdCmsBlock_In($cmsBlockIds)
            ->joinWithCmsBlockTemplate()
            ->joinWithSpyCmsBlockGlossaryKeyMapping()
            ->useSpyCmsBlockGlossaryKeyMappingQuery()
                ->joinWithGlossaryKey()
            ->endUse()
            ->joinWithSpyCmsBlockStore()
            ->useSpyCmsBlockStoreQuery()
                ->joinWithSpyStore()
            ->endUse()
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $query;
    }

    /**
     * @api
     *
     * @param int[] $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIds(array $cmsBlockIds): SpyCmsBlockQuery
    {
        return $this->getFactory()
            ->createCmsBlockQuery()
            ->filterByIdCmsBlock_In($cmsBlockIds);
    }
}
