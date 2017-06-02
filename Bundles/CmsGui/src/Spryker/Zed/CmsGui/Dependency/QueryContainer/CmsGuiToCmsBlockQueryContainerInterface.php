<?php


namespace Spryker\Zed\CmsGui\Dependency\QueryContainer;


use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;

interface CmsGuiToCmsBlockQueryContainerInterface
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

}