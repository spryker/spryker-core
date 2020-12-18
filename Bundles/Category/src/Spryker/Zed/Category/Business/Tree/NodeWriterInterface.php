<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

interface NodeWriterInterface
{
    /**
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateOrder($idCategoryNode, $position): void;
}
