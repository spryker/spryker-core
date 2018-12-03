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
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 */
class CategoryImageFacade extends AbstractFacade implements CategoryImageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImagesSetsByCategoryId(int $idCategory): array
    {
        return $this->getFactory()
            ->createCategoryImageReader()
            ->getCategoryImagesSetCollectionByIdCategory($idCategory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->createCategoryImageSetsForCategory($categoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryImageWriter()
            ->updateCategoryImageSetsForCategory($categoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryImageSetsByIdCategory(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryImageWriter()
            ->deleteCategoryImageSetsByIdCategory($idCategory);
    }

    /**
     * {@inheritdoc}
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
            ->expandCategoryWithImageSetCollection($categoryTransfer);
    }
}
