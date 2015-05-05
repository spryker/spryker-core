<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface ClosureTableWriterInterface
{

    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     */
    public function create(CategoryCategoryNodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return void
     */
    public function delete($nodeId);
}
