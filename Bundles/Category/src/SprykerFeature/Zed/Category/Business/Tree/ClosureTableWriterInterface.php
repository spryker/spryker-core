<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use SprykerFeature\Shared\Category\Transfer\CategoryNode;

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