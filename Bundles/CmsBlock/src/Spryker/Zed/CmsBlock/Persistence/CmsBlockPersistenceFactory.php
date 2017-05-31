<?php


namespace Spryker\Zed\CmsBlock\Persistence;


use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CmsBlockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function createCmsBlockQuery()
    {
        return SpyCmsBlockQuery::create();
    }

    /**
     * @return SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function createCmsBlockGlossaryKeyMappingQuery()
    {
        return SpyCmsBlockGlossaryKeyMappingQuery::create();
    }

    /**
     * @return SpyCmsBlockTemplateQuery
     */
    public function createCmsBlockTemplateQuery()
    {
        return SpyCmsBlockTemplateQuery::create();
    }

}