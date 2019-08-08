<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\Category;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Spryker\Zed\Category\Business\Exception\MissingCategoryException;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class Category implements CategoryInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface
     */
    protected $categoryHydrator;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface $categoryHydrator
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryRepositoryInterface $categoryRepository,
        CategoryHydratorInterface $categoryHydrator
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryRepository = $categoryRepository;
        $this->categoryHydrator = $categoryHydrator;
    }

    /**
     * @deprecated Use \Spryker\Zed\Category\Business\Model\CategoryReaderInterface::findCategoryById() instead.
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory, CategoryTransfer $categoryTransfer)
    {
        $categoryEntity = $this
            ->queryContainer
            ->queryCategoryById($idCategory)
            ->findOne();

        if (!$categoryEntity) {
            throw new MissingCategoryException(sprintf('Could not find category for id "%s"', $idCategory));
        }

        $categoryTransfer->fromArray($categoryEntity->toArray(), true);

        return $categoryTransfer;
    }

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

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $categoryEntity = $this->getCategoryEntity($categoryTransfer->requireIdCategory()->getIdCategory());

        $categoryEntity->fromArray($categoryTransfer->toArray());
        $categoryEntity->save();
    }

    /**
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        $categoryEntity = $this
            ->queryContainer
            ->queryCategoryById($idCategory)
            ->findOne();

        if (!$categoryEntity) {
            throw new MissingCategoryException(sprintf('Could not find category for ID "%s"', $idCategory));
        }

        return $categoryEntity;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $categoryEntity = $this->getCategoryEntity($idCategory);
        $categoryEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $categoryCollectionTransfer = $this->categoryRepository->getAllCategoryCollection($localeTransfer);
        $this->categoryHydrator->hydrateCategoryCollection($categoryCollectionTransfer, $localeTransfer);

        return $categoryCollectionTransfer;
    }
}
