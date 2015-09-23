<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface ClosureTableWriterInterface
{

    /**
     * @param NodeTransfer $categoryNode
     */
    public function create(NodeTransfer $categoryNode);

    /**
     * @param NodeTransfer $categoryNode
     */
    public function moveNode(NodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     */
    public function delete($nodeId);

}
