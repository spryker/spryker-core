<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;

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
    public function findCategoryImageSetsByCategoryId(int $idCategory, array $excludeIdCategoryImageSets = []): array;

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findDefaultCategoryImageSets(int $idCategory): array;

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findLocalizedCategoryImageSets(int $idCategory, int $idLocale): array;

    /**
     * @param int|null $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function findOrCreateCategoryImageSetById(?int $idCategoryImageSet): CategoryImageSetTransfer;

    /**
     * @param int $idCategoryImageSet
     * @param array $excludeIdCategoryImage
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function findCategoryImagesByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = []): array;
}
