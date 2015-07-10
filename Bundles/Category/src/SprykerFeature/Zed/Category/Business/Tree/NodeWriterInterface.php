<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface NodeWriterInterface
{

    /**
     * @param NodeTransfer $categoryNode
     *
     * @return int $nodeId
     */
    public function create(NodeTransfer $categoryNode);

    /**
     * @param NodeTransfer $categoryNode
     */
    public function update(NodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId);

}
