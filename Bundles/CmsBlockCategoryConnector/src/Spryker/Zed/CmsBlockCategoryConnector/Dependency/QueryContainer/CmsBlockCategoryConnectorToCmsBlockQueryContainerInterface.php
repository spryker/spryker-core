<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;


interface CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate();

}