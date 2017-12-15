<?php


namespace Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer;


interface CategoryNavigationConnectorToCategoryQueryContainerInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode);
}