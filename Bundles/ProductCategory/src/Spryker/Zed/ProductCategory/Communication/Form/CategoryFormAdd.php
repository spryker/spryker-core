<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Form;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryFormAdd extends AbstractForm
{

    const NAME = 'name';
    const PK_CATEGORY = 'id_category';
    const CATEGORY_KEY = 'category_key';
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::CATEGORY_KEY, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FK_PARENT_CATEGORY_NODE, new Select2ComboBoxType(), [
                'label' => 'Parent',
                'choices' => $this->getCategoriesWithPaths($this->locale->getIdLocale()),
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::PK_CATEGORY_NODE, 'hidden');
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }

    /**
     * @param int $idLocale
     *
     * @return array
     */
    protected function getCategoriesWithPaths($idLocale)
    {
        $categoryEntityList = $this->categoryQueryContainer
            ->queryCategory($this->locale->getIdLocale())
            ->find();

        $categories = [];
        $pathCache = [];
        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                if (!array_key_exists($nodeEntity->getFkParentCategoryNode(), $pathCache)) {
                    $path = $this->buildPath($nodeEntity);
                } else {
                    $path = $pathCache[$nodeEntity->getFkParentCategoryNode()];
                }

                $categories[$path][$nodeEntity->getIdCategoryNode()] = $categoryEntity
                    ->getLocalisedAttributes($idLocale)
                    ->getFirst()
                    ->getName();
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
            ->find();

        $formattedPath = [];
        foreach ($pathTokens as $path) {
            $formattedPath[] = $path['name'];
        }

        return '/' . implode('/', $formattedPath);
    }

    /**
     * @return array
     */
    protected function getAssignedProducts()
    {
        $productEntityList = $this->productCategoryQueryContainer
            ->queryProductsByCategoryId($this->idCategory, $this->locale)
            ->find();

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
            ->find();

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
    public function populateFormFields()
    {
        $fields = $this->getDefaultFormFields();

        /** @var SpyCategory $categoryEntity */
        $categoryEntity = $this->categoryQueryContainer
            ->queryCategoryById($this->idCategory)
            ->innerJoinAttribute()
            ->addAnd(SpyCategoryAttributeTableMap::COL_FK_LOCALE, $this->locale->getIdLocale())
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->findOne();

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
