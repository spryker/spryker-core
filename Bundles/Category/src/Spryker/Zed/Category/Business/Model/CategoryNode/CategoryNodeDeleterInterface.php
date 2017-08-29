<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

interface CategoryNodeDeleterInterface
{

    /**
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return void
     */
    public function deleteNodeById($idCategoryNode, $idChildrenDestinationNode);

}
