<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
        foreach ($urlEntities as $urlEntity) {
            $navigationNodeEntities = $this->navigationQueryContainer->queryNavigationNodeByFkUrl($urlEntity->getIdUrl())->find();
            foreach ($navigationNodeEntities as $navigationNode) {
                $navigationNodes[] = (new NavigationNodeTransfer())->fromArray($navigationNode->toArray(), true);
            }
        }

        return $navigationNodes;
    }
}
