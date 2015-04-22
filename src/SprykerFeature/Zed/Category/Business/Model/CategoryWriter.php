<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttribute;
use SprykerFeature\Shared\Category\Transfer\Category as CategoryTransfer;
use Propel\Runtime\Exception\PropelException;

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
     * @param int $idLocale
     *
     * @return SpyCategory
     * @throws \ErrorException
     */
    public function create(CategoryTransfer $category, $idLocale)
    {
        $idCategory = $this->persistCategory();
        $category->setIdCategory($idCategory);
        $this->persistCategoryAttribute($category, $idLocale);

        return $idCategory;
    }

    /**
     * @param CategoryTransfer $category
     * @param string $idLocale
     * @return int
     *
     * @throws PropelException
     */
    public function update(CategoryTransfer $category, $idLocale)
    {
        $attributeEntity = $this->getAttributeEntity($category->getIdCategory(), $idLocale);
        $attributeEntity->setName($category->getName());
        $attributeEntity->save();

        return $attributeEntity->getIdCategoryAttribute();
    }

    /**
     * @param int $idCategory
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
     * @param int $idLocale
     * @throws PropelException
     */
    protected function persistCategoryAttribute(CategoryTransfer $category, $idLocale)
    {
        $categoryAttributeEntity = $this->locator->category()->entitySpyCategoryAttribute();

        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setName($category->getName());
        $categoryAttributeEntity->setFkLocale($idLocale);

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
     * @param int $idLocale
     * @return SpyCategoryAttribute
     */
    protected function getAttributeEntity($idCategory, $idLocale)
    {
        return $this->queryContainer
            ->queryAttributeByCategoryIdAndLocale($idCategory, $idLocale)
            ->findOne()
            ;
    }
}
