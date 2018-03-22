<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface ClosureTableWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function create(NodeTransfer $categoryNodeTransfer);

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function moveNode(NodeTransfer $categoryNodeTransfer);

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
