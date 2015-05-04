<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface NodeWriterInterface
{

    /**
     * @param CategoryNode $categoryNode
     *
     * @return int $nodeId
     */
    public function create(CategoryNode $categoryNode);

    /**
     * @param CategoryNode $categoryNode
     */
    public function update(CategoryNode $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId);
}
