<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Persistence;

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
    public function queryCmsBlockStorageEntities(array $cmsBlockIds)
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
    public function queryBlockWithRelationsByIds(array $cmsBlockIds)
    {
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
}
