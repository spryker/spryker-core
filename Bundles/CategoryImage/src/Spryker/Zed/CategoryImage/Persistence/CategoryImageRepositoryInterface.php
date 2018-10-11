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
     * @param int $categoryId
     * @param array $excludeIdCategoryImageSets
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsByCategoryId(int $categoryId, array $excludeIdCategoryImageSets = []);

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImage|null
     */
    public function findImageSetById(int $idCategoryImageSet);

    /**
     * @param int $idCategoryImageSet
     * @param array $excludeIdCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsToCategoryImageByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = []);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findDefaultCategoryImageSets(int $idCategory);

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findLocalizedCategoryImageSets(int $idCategory, int $idLocale);
}
