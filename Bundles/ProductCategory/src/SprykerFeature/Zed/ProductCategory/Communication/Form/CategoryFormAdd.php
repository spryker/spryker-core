<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;

class CategoryFormAdd extends AbstractForm
{

    const NAME = 'name';
    const PK_CATEGORY = 'id_category';
    const PK_CATEGORY_NODE = 'id_category_node';
    const FK_PARENT_CATEGORY_NODE = 'fk_parent_category_node';
    const FK_NODE_CATEGORY = 'fk_category';

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var int
     */
    protected $idParentNode;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     * @param int $idParentNode
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        LocaleTransfer $locale,
        $idCategory,
        $idParentNode
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
        $this->idParentNode = $idParentNode;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        return $this->addText(self::NAME, [
                'constraints' => [
                    $this->locateConstraint()->createConstraintNotBlank(),
                ],
            ])
            ->addSelect2ComboBox(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Parent',
                'choices' => $this->getCategoriesWithPaths(),
                'constraints' => [
                    $this->locateConstraint()->createConstraintNotBlank(),
                ],
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
        ;
    }

    /**
     * @return array
     */
    protected function getCategoriesWithPaths()
    {
        $categoryEntityList = $this->categoryQueryContainer
            ->queryCategory($this->locale->getIdLocale())
            ->find()
        ;

        $categories = [];
        $pathCache = [];
        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                if (!array_key_exists($nodeEntity->getFkParentCategoryNode(), $pathCache)) {
                    $path = $this->buildPath($nodeEntity);
                } else {
                    $path = $pathCache[$nodeEntity->getFkParentCategoryNode()];
                }

                $categories[$path][$nodeEntity->getIdCategoryNode()] = $categoryEntity->getAttributes()->getFirst()->getName();
            }
        }

        $categories = $this->sortCategoriesWithPaths($categories);

        return $categories;
    }

    /**
     * @param array $categories
     *
     * @return array
     */
    protected function sortCategoriesWithPaths(array $categories)
    {
        ksort($categories);

        foreach ($categories as $path => $categoryNames) {
            asort($categories[$path], SORT_FLAG_CASE & SORT_STRING);
        }

        return $categories;
    }

    /**
     * @param SpyCategoryNode $node
     *
     * @return string
     */
    protected function buildPath(SpyCategoryNode $node)
    {
        $pathTokens = $this->categoryQueryContainer
            ->queryPath($node->getIdCategoryNode(), $this->locale->getIdLocale(), false, true)
            ->find()
        ;

        $formattedPath = [];
        foreach ($pathTokens as $path) {
            $formattedPath[] = $path['name'];
        }

        return  '/' . implode('/', $formattedPath);
    }

    /**
     * @return array
     */
    protected function getAssignedProducts()
    {
        $productEntityList = $this->productCategoryQueryContainer
            ->queryProductsByCategoryId($this->idCategory, $this->locale)
            ->find()
        ;

        $assignedProducts = [];
        foreach ($productEntityList as $productEntity) {
            /* @var SpyProductCategory $productEntity */
            $assignedProducts[] = $productEntity->getIdProductCategory();
        }

        return $assignedProducts;
    }

    /**
     * @return array
     */
    protected function getProducts()
    {
        $productCategoryEntityList = $this->productCategoryQueryContainer
            ->queryProductsByCategoryId($this->idCategory, $this->locale)
            ->find()
        ;

        $products = [];
        foreach ($productCategoryEntityList as $productCategoryEntity) {
            /* @var SpyProductCategory $productCategoryEntity */
            $products[$productCategoryEntity->getIdProductCategory()] = $productCategoryEntity->getName();
        }

        return $products;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $fields = $this->getDefaultFormFields();

        /** @var SpyCategory $categoryEntity */
        $categoryEntity = $this->categoryQueryContainer
            ->queryCategoryById($this->idCategory)
            ->innerJoinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->findOne()
        ;

        if ($categoryEntity) {
            $categoryEntity = $categoryEntity->toArray();

            $fields = [
                self::PK_CATEGORY => $categoryEntity[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $categoryEntity[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $categoryEntity[self::FK_PARENT_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $categoryEntity[self::FK_PARENT_CATEGORY_NODE],
                self::NAME => $categoryEntity[self::NAME],
            ];
        }

        return $fields;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            self::PK_CATEGORY => null,
            self::PK_CATEGORY_NODE => null,
            self::FK_PARENT_CATEGORY_NODE => $this->idParentNode,
            self::NAME => '',
        ];
    }

}
