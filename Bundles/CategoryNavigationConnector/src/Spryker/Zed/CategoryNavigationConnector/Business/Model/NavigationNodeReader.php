<?php

namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToCategoryQueryContainerInterface;
use Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToNavigationQueryContainerInterface;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\Business\CategoryNavigationConnectorBusinessFactory getFactory()
 */
class NavigationNodeReader implements NavigationNodeReaderInterface
{

    /**
     * @var \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;
    /**
     * @var \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToNavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToNavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct(CategoryNavigationConnectorToCategoryQueryContainerInterface $categoryQueryContainer, CategoryNavigationConnectorToNavigationQueryContainerInterface $navigationQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer []
     */
    public function getNavigationNodesFromCategoryNodeId($idCategoryNode)
    {
        $navigationNodes = [];
        $urlEntities = $this->categoryQueryContainer->queryResourceUrlByCategoryNodeId($idCategoryNode)->find();
        foreach ($urlEntities as $url) {
            $navigationNodeEntities = $this->navigationQueryContainer->queryNavigationNodeByFkUrl($url->getIdUrl())->find();
            foreach($navigationNodeEntities as $navigationNode) {
                $navigationNodes[] = (new NavigationNodeTransfer())->fromArray($navigationNode->toArray(), true);
            }
        }

        return $navigationNodes;
    }
}
