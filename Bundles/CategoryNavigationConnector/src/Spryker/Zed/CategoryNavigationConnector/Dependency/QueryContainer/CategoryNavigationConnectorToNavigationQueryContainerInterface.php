<?php


namespace Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer;


interface CategoryNavigationConnectorToNavigationQueryContainerInterface
{
    /**
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl);
}