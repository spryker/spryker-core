<?php


namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

interface NavigationNodeReaderInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function getNavigationNodesFromCategoryNodeId($idCategoryNode);
}