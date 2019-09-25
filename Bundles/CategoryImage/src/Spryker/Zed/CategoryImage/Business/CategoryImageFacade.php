<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use ArrayObject;
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImageSetsByIdCategory(int $idCategory): array
    {
        return $this->getFactory()
            ->createImageSetReader()
            ->getCategoryImageSetsByIdCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): ArrayObject
    {
        return $this->getFactory()
            ->createImageSetCreator()
            ->createCategoryImageSetsForCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getFactory()
            ->createImageSetUpdated()
            ->updateCategoryImageSetsForCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
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
            ->createImageSetDeleter()
            ->deleteCategoryImageSetsByIdCategory($idCategory);
    }

    /**
     * {@inheritDoc}
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
            ->createCategoryExpander()
            ->expandCategoryWithImageSets($categoryTransfer);
    }
}
