<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageBusinessFactory getFactory()
 */
class CategoryImageFacade extends AbstractFacade implements CategoryImageFacadeInterface
{
    /**
     * Specification:
     * - Creates a new category image entity or updates an existing one if the ID is provided and the entity already exists.
     * - Returns a CategoryImageTransfer with the ID of the persisted entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveProductImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->saveCategoryImage($categoryImageTransfer);
    }

    /**
     * Specification:
     * - Creates a new category image set entity or updates an existing one if the ID is provided and the entity already exists.
     * - Creates new category image entities or update existing ones if their ID is provided and the entities already exists.
     * - Returns a CategoryImageSetTransfer with the IDs of the persisted entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->saveCategoryImageSet($categoryImageSetTransfer);
    }

    /**
     * Specification:
     * - Returns all category image sets from database for the given category id.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        return $this->getFactory()
            ->createCategoryImageReader()
            ->findCategoryImagesSetCollectionByCategoryId($idCategory);
    }

    /**
     * Specification:
     * - Persists all provided image sets to database for the given category.
     * - Returns CategoryTransfer along with the data from the persisted CategoryImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSetCollection(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->createCategoryImageSetCollection($categoryTransfer);
    }

    /**
     * Specification:
     * - Persists all provided image sets to database for the given category.
     * - Returns CategoryTransfer along with the data from the persisted CategoryImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSetCollection(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->updateCategoryImageSetCollection($categoryTransfer);
    }

    /**
     * Specification:
     * - Expands the CategoryTransfer with the category's image sets from database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryImageReader()
            ->expandProductAbstractWithImageSets($categoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    public function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $this->getFactory()
            ->createCategoryImageWriter()
            ->deleteCategoryImageSet($categoryImageSetTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCombinedCategoryImageSets($idCategory, $idLocale): array
    {
        return $this->getFactory()
            ->createCategoryImageSetCombiner()
            ->getCombinedCategoryImageSets($idCategory, $idLocale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|null
     */
    public function findProductImageSetById($idCategoryImageSet): ?CategoryImageSetTransfer
    {
        return $this->getFactory()
            ->createCategoryImageReader()
            ->findCategoryImagesSetCollectionById($idCategoryImageSet);
    }
}
