<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
interface CategoryImageEntityManagerInterface
{
    /**
     * @param int $idCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage
     */
    public function findOrCreateCategoryImageById(int $idCategoryImage): SpyCategoryImage;

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    public function findOrCreateCategoryImageSetById(int $idCategoryImageSet): SpyCategoryImageSet;

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage
     */
    public function findOrCreateCategoryImageRelation(int $idCategoryImageSet, int $idCategoryImage);
}
