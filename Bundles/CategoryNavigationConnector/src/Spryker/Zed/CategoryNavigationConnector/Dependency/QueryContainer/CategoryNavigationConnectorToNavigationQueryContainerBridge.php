<?php

namespace Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer;

class CategoryNavigationConnectorToNavigationQueryContainerBridge implements CategoryNavigationConnectorToNavigationQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct($navigationQueryContainer)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl)
    {
        return $this->navigationQueryContainer->queryNavigationNodeByFkUrl($fkUrl);
    }
}
