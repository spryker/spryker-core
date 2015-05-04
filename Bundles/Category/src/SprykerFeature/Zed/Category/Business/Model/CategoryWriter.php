<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\CategoryCategory as CategoryTransferTransfer;
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
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        CategoryQueryContainer $queryContainer
    ) {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @return SpyCategory
     * @throws \ErrorException
     */
    public function create(CategoryTransfer $category, LocaleDto $locale)
    {
        $idCategory = $this->persistCategory();
        $category->setIdCategory($idCategory);
        $this->persistCategoryAttribute($category, $locale);

        return $idCategory;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleDto $locale
     * @return int
     *
     * @throws PropelException
     */
    public function update(CategoryTransfer $category, LocaleDto $locale)
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
        $categoryEntity = $this->locator->category()->entitySpyCategory();

        $categoryEntity->setIsActive(true);
        $categoryEntity->save();

        return $categoryEntity->getPrimaryKey();
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @throws PropelException
     */
    protected function persistCategoryAttribute(CategoryTransfer $category, LocaleDto $locale)
    {
        $categoryAttributeEntity = $this->locator->category()->entitySpyCategoryAttribute();

        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setName($category->getName());
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
     * @param LocaleDto $locale
     *
     * @return SpyCategoryAttribute
     */
    protected function getAttributeEntity($idCategory, LocaleDto $locale)
    {
        return $this->queryContainer
            ->queryAttributeByCategoryIdAndLocale($idCategory, $locale->getIdLocale())
            ->findOne()
            ;
    }
}
