<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

interface CategoryToucherInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeActiveRecursively($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeActive($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeDeletedRecursively($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeDeleted($idCategoryNode);

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive(int $idCategory): void;

    /**
     * @param int $idFormerParentCategoryNode
     *
     * @return void
     */
    public function touchFormerParentCategoryNodeActiveRecursively($idFormerParentCategoryNode);
}
