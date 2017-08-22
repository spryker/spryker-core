<?php


namespace Spryker\Zed\Category\Business\Model\CategoryNode;


use Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException;

interface CategoryNodeDeleterInterface
{

    /**
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @throws MissingCategoryNodeException
     *
     * @return void
     */
    public function deleteNodeById($idCategoryNode, $idChildrenDestinationNode);

}