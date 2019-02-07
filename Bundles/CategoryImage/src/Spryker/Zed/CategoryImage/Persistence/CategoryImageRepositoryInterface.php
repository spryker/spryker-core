<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
interface CategoryImageRepositoryInterface
{
    /**
     * @param int $idCategory
     * @param array $excludeIdCategoryImageSets
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImageSetsByIdCategory(int $idCategory, array $excludeIdCategoryImageSets = []): array;

    /**
     * @param int $idCategoryImageSet
     * @param array $excludeIdCategoryImage
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function getCategoryImagesByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = []): array;
}
