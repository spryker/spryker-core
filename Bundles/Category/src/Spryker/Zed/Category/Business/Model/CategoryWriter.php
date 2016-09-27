<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryWriter implements CategoryWriterInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    public function create(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        $categoryEntity = new SpyCategory();
        $categoryEntity->fromArray($categoryTransfer->toArray());
        $categoryEntity->save();

        $idCategory = $categoryEntity->getPrimaryKey();
        $categoryTransfer->setIdCategory($idCategory);

        $this->persistCategoryAttribute($categoryTransfer, $localeTransfer);

        return $idCategory;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $this->persistCategoryAttribute($categoryTransfer, $localeTransfer);

        $this->saveCategory($categoryTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->deleteAttributes($idCategory);
        $categoryEntity = $this->queryContainer->queryCategoryById($idCategory)
            ->findOne();

        if ($categoryEntity) {
            $categoryEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function saveCategory(CategoryTransfer $categoryTransfer)
    {
        $categoryEntity = $this->getCategoryEntity($categoryTransfer->getIdCategory());
        $categoryEntity->fromArray($categoryTransfer->toArray());
        $categoryEntity->save();

        return $categoryEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    protected function persistCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        $categoryAttributeEntity = $this->queryContainer->queryAttributeByCategoryId($categoryTransfer->getIdCategory())
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->findOneOrCreate();

        $this->saveCategoryAttribute($categoryTransfer, $localeTransfer, $categoryAttributeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $categoryAttributeEntity = new SpyCategoryAttribute();

        $this->saveCategoryAttribute($categoryTransfer, $localeTransfer, $categoryAttributeEntity);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function deleteAttributes($idCategory)
    {
        $attributeCollection = $this->queryContainer
            ->queryAttributeByCategoryId($idCategory)
            ->find();

        foreach ($attributeCollection as $attributeEntity) {
            $attributeEntity->delete();
        }
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    protected function getAttributeEntity($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->queryContainer
            ->queryAttributeByCategoryIdAndLocale($idCategory, $localeTransfer->getIdLocale())
            ->findOne();
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        return $this->queryContainer->queryCategoryById($idCategory)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return void
     */
    protected function saveCategoryAttribute(
        CategoryTransfer $categoryTransfer,
        LocaleTransfer $localeTransfer,
        SpyCategoryAttribute $categoryAttributeEntity
    ) {
        $categoryAttributeEntity->fromArray($categoryTransfer->toArray());
        $categoryAttributeEntity->setFkCategory($categoryTransfer->getIdCategory());
        $categoryAttributeEntity->setFkLocale($localeTransfer->getIdLocale());

        $categoryAttributeEntity->save();
    }

}
