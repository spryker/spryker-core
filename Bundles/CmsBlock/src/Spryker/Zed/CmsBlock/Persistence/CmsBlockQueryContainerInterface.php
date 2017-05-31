<?php


namespace Spryker\Zed\CmsBlock\Persistence;


use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;

interface CmsBlockQueryContainerInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock);

}