<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface ClosureTableWriterInterface
{

    /**
     * @param NodeTransfer $categoryNode
     */
    public function create(NodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return void
     */
    public function delete($nodeId);
}
