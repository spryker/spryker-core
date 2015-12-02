<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;

class CategoryWriter implements CategoryWriterInterface
{

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(CategoryQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @throws \ErrorException
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
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
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
     * @throws PropelException
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
     * @param CategoryTransfer $category
     *
     * @throws PropelException
     *
     * @return SpyCategory
     */
    protected function saveCategory(CategoryTransfer $category)
    {
        $categoryEntity = $this->getCategoryEntity($category->getIdCategory());
        $categoryEntity->fromArray($category->toArray());
        $categoryEntity->save();

        return $categoryEntity;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
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
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
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
     * @param LocaleTransfer $locale
     *
     * @return SpyCategoryAttribute
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
     * @return SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        return $this->queryContainer->queryCategoryById($idCategory)->findOne();
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     * @param SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return void
     */
    protected function saveCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale,
        SpyCategoryAttribute $categoryAttributeEntity
    ) {
        $categoryAttributeEntity->fromArray($category->toArray());
        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setFkLocale($locale->getIdLocale());

        $categoryAttributeEntity->save();
    }

}
