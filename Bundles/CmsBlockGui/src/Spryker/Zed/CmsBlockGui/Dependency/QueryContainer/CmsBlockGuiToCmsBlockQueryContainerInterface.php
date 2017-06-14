<?php


namespace Spryker\Zed\CmsBlockGui\Dependency\QueryContainer;


use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;

interface CmsBlockGuiToCmsBlockQueryContainerInterface
{
    /**
     * @param string $name
     *
     * @return SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name);

    /**
     * @return SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate();

    /**
     * @return SpyCmsBlockTemplateQuery
     */
    public function queryTemplates();

}