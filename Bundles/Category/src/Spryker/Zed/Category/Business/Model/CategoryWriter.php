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
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function create(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $categoryEntity = new SpyCategory();
        $categoryEntity->fromArray($category->toArray());
        $categoryEntity->save();

        $idCategory = $categoryEntity->getPrimaryKey();
        $category->setIdCategory($idCategory);

        $this->persistCategoryAttribute($category, $locale);

        return $idCategory;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function update(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->persistCategoryAttribute($category, $locale);

        $this->saveCategory($category);
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function saveCategory(CategoryTransfer $category)
    {
        $categoryEntity = $this->getCategoryEntity($category->getIdCategory());
        $categoryEntity->fromArray($category->toArray());
        $categoryEntity->save();

        return $categoryEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function persistCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $categoryAttributeEntity = $this->queryContainer->queryAttributeByCategoryId($category->getIdCategory())
            ->filterByFkLocale($locale->getIdLocale())
            ->findOneOrCreate();

        $this->saveCategoryAttribute($category, $locale, $categoryAttributeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $categoryAttributeEntity = new SpyCategoryAttribute();

        $this->saveCategoryAttribute($category, $locale, $categoryAttributeEntity);
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    protected function getAttributeEntity($idCategory, LocaleTransfer $locale)
    {
        return $this->queryContainer
            ->queryAttributeByCategoryIdAndLocale($idCategory, $locale->getIdLocale())
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return void
     */
    protected function saveCategoryAttribute(
        CategoryTransfer $category,
        LocaleTransfer $locale,
        SpyCategoryAttribute $categoryAttributeEntity
    ) {
        $categoryAttributeEntity->fromArray($category->toArray());
        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setFkLocale($locale->getIdLocale());

        $categoryAttributeEntity->save();
    }

}
