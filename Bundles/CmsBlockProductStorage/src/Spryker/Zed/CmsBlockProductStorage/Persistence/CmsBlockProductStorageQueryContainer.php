<?php

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStoragePersistenceFactory getFactory()
 */
class CmsBlockProductStorageQueryContainer extends AbstractQueryContainer implements CmsBlockProductStorageQueryContainerInterface
{
    const NAME = 'name';

    /**
     * @param array $productIds
     *
     * @return $this|SpyCmsBlockProductStorageQuery
     */
    public function queryCmsBlockProductStorageByIds(array $productIds)
    {
        return $this->getFactory()
            ->createSpyCmsBlockProductStorageQuery()
            ->filterByFkProductAbstract_In($productIds);
    }

    /**
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProducts(array $productIds)
    {
        return $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByFkProductAbstract_In($productIds);
    }
}
