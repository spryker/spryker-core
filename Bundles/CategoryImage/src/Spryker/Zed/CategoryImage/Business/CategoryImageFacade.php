<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageBusinessFactory getFactory()
 */
class CategoryImageFacade extends AbstractFacade implements CategoryImageFacadeInterface
{
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
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array
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
            ->expandCategoryWithImageSets($categoryTransfer);
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
}
