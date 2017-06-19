<?php


namespace Spryker\Zed\CmsBlockProductConnector\Persistence;


use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CmsBlockProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyCmsBlockProductConnectorQuery
     */
    public function createCmsBlockProductConnectorQuery()
    {
        return SpyCmsBlockProductConnectorQuery::create();
    }

}