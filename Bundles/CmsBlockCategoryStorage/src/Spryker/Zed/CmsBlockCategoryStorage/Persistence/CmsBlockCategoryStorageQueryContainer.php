<?php

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryPositionTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStoragePersistenceFactory getFactory()
 */
class CmsBlockCategoryStorageQueryContainer extends AbstractQueryContainer implements CmsBlockCategoryStorageQueryContainerInterface
{
    const POSITION = 'position';
    const NAME = 'name';

    /**
     * @param array $categoryIds
     *
     * @return $this|SpyCmsBlockCategoryStorageQuery
     */
    public function queryCmsBlockCategoryStorageByIds(array $categoryIds)
    {
        return $this->getFactory()
            ->createSpyCmsBlockCategoryStorageQuery()
            ->filterByFkCategory_In($categoryIds);
    }

    /**
     * @param array $categoryIds
     *
     * @return $this|\Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategories(array $categoryIds)
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->innerJoinCmsBlockCategoryPosition()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockCategoryPositionTableMap::COL_NAME, static::POSITION)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByFkCategory_In($categoryIds);
    }

    /**
     * @param array $idPositions
     *
     * @return SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCategoryIdsByPositionIds(array $idPositions)
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlockCategoryPosition_In($idPositions)
            ->select([SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY]);
    }
}
