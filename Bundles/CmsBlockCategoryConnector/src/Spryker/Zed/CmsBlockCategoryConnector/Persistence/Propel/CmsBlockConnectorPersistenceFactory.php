<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;


use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockConnectorQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CmsBlockConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return SpyCmsBlockConnectorQuery
     */
    public function createCmsBlockConnetorQuery()
    {
        return SpyCmsBlockConnectorQuery::create();
    }

}