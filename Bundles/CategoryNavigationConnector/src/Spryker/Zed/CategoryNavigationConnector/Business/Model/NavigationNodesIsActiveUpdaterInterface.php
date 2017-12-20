<?php


namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

interface NavigationNodesIsActiveUpdaterInterface
{
    /**
     * @param int $idCategoryNode
     * @param bool $isActive
     */
    public function updateCategoryNodeNavigationNodes($idCategoryNode, $isActive);
}