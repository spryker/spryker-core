<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTree;

interface CategoryTreeInterface
{

    /**
     * @param int $idSourceCategoryNode
     * @param int $idDestinationCategoryNode
     *
     * @return int
     */
    public function moveSubTree($idSourceCategoryNode, $idDestinationCategoryNode);

}
