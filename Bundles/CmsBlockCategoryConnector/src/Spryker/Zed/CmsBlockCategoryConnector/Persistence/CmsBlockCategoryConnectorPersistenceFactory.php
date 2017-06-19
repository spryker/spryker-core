<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;


use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockConnectorQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CmsBlockCategoryConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return SpyCmsBlockCategoryConnectorQuery
     */
    public function createCmsBlockCategoryConnectorQuery()
    {
        return SpyCmsBlockCategoryConnectorQuery::create();
    }

}