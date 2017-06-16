<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;


interface CategoryQueryContainerInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

}