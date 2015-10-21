<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Category\CategoryInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttribute;

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
     * @param CategoryInterface $category
     * @param LocaleTransfer $locale
     *
     * @throws \ErrorException
     *
     * @return int
     */
    public function create(CategoryInterface $category, LocaleTransfer $locale)
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
     * @param CategoryInterface $category
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function update(CategoryInterface $category, LocaleTransfer $locale)
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
        $categoryEntity = $this->queryContainer
            ->queryCategoryById($idCategory)
            ->findOne()
        ;

        if ($categoryEntity) {
            $categoryEntity->delete();
        }
    }

    /**
     * @param CategoryInterface $category

     *
     * @throws PropelException
     *
     * @return SpyCategory
     */
    protected function saveCategory(CategoryInterface $category)
    {
        $categoryEntity = $this->getCategoryEntity($category->getIdCategory());
        $categoryEntity->fromArray($category->toArray());
        $categoryEntity->save();

        return $categoryEntity;
    }

    /**
     * @param CategoryInterface $category
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     *
     * @return void
     */
    protected function persistCategoryAttribute(CategoryInterface $category, LocaleTransfer $locale)
    {
        $categoryAttributeEntity = $this->queryContainer->queryAttributeByCategoryId($category->getIdCategory())->findOneOrCreate();

        $categoryAttributeEntity->fromArray($category->toArray());
        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setFkLocale($locale->getIdLocale());

        $categoryAttributeEntity->save();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function deleteAttributes($idCategory)
    {
        $attributeCollection = $this->queryContainer->queryAttributeByCategoryId($idCategory)->find();
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
            ->findOne()
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        return $this->queryContainer
            ->queryCategoryById($idCategory)
            ->findOne()
        ;
    }

}
