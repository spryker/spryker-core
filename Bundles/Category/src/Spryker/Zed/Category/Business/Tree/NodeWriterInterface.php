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
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return int
     */
    public function create(NodeTransfer $categoryNodeTransfer);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function update(NodeTransfer $categoryNodeTransfer);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     *
     * @return bool
     */
    public function delete($idCategoryNode);

    /**
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateOrder($idCategoryNode, $position);
}
