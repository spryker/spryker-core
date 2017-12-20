<?php


namespace Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer;


interface CmsNavigationConnectorToNavigationQueryContainerInterface
{
    /**
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl);
}