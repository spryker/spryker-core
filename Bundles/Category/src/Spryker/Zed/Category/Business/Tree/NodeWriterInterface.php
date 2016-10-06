<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;

interface NodeWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return int $nodeId
     */
    public function create(NodeTransfer $categoryNodeTransfer);

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    public function update(NodeTransfer $categoryNode);

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId);

}
