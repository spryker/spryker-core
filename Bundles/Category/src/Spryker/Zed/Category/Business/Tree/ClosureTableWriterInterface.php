<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface ClosureTableWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    public function create(NodeTransfer $categoryNode);

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    public function moveNode(NodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId);

    /**
     * @return void
     */
    public function rebuildCategoryNodes();

}
