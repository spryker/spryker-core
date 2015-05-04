<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface NodeWriterInterface
{

    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     *
     * @return int $nodeId
     */
    public function create(CategoryCategoryNodeTransfer $categoryNode);

    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     */
    public function update(CategoryCategoryNodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId);
}
