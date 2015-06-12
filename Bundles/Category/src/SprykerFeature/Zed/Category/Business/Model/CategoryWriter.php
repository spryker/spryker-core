<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryCategory as CategoryTransferTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
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
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return SpyCategory
     * @throws \ErrorException
     */
    public function create(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $idCategory = $this->persistCategory();
        $category->setIdCategory($idCategory);
        $this->persistCategoryAttribute($category, $locale);

        return $idCategory;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     * @return int
     *
     * @throws PropelException
     */
    public function update(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $attributeEntity = $this->getAttributeEntity($category->getIdCategory(), $locale);
        $attributeEntity->setName($category->getName());
        $attributeEntity->save();

        return $attributeEntity->getIdCategoryAttribute();
    }

    /**
     * @param int $idCategory
     *
     * @throws PropelException
     */
    public function delete($idCategory)
    {
        $this->deleteAttributes($idCategory);
        $categoryEntity = $this->queryContainer->queryCategoryById($idCategory)->findOne();

        if ($categoryEntity) {
            $categoryEntity->delete();
        }
    }

    /**
     * @return int
     * @throws PropelException
     */
    protected function persistCategory()
    {
        $categoryEntity = new SpyCategory();

        $categoryEntity->setIsActive(true);
        $categoryEntity->save();

        return $categoryEntity->getPrimaryKey();
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     */
    protected function persistCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $categoryAttributeEntity = new SpyCategoryAttribute();

        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setName($category->getName());
        $categoryAttributeEntity->setCategoryImageName($category->getImageName());
        $categoryAttributeEntity->setFkLocale($locale->getIdLocale());

        $categoryAttributeEntity->save();
    }

    /**
     * @param int $idCategory
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
}
