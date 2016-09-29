<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\Category;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;

class Category implements CategoryInterface
{

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $categoryEntity = new SpyCategory();
        $categoryEntity->fromArray($categoryTransfer->toArray());
        $categoryEntity->save();

        $idCategory = $categoryEntity->getPrimaryKey();
        $categoryTransfer->setIdCategory($idCategory);
    }

}
