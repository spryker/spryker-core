<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface ClosureTableWriterInterface
{

    /**
     * @param CategoryNode $categoryNode
     */
    public function create(CategoryNode $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return void
     */
    public function delete($nodeId);
}
