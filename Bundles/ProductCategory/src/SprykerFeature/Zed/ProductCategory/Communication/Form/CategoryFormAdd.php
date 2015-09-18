<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Base\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategory;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormAdd extends AbstractForm
{

    const NAME = 'name';
    const PK_CATEGORY = 'id_category';
    const PK_CATEGORY_NODE = 'id_category_node';
    const FK_PARENT_CATEGORY_NODE = 'fk_parent_category_node';

    /**
     * @var CategoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @var ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param CategoryQueryContainer $categoryQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(CategoryQueryContainer $categoryQueryContainer, ProductCategoryFacade $productCategoryFacade, LocaleTransfer $locale, $idCategory)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productCategoryFacade = $productCategoryFacade;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
    }

    /**
     * @return CategoryFormAdd
     */
    protected function buildFormFields()
    {
        return $this->addText(self::NAME, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
            ->addSelect2ComboBox(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Parent',
                'choices' => $this->getCategories(),
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ;
    }

    /**
     * @return array
     */
    protected function getCategories()
    {
        $categories = $this->categoryQueryContainer->queryCategory($this->locale->getIdLocale())
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        $data = [];
        foreach ($categories as $category) {
            $data[$category[self::PK_CATEGORY]] = $category[self::NAME];
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getAssignedProducts()
    {
        $productList = $this->productCategoryFacade->getProductsByCategory($this->idCategory, $this->locale);

        $data = [];
        foreach ($productList as $product) {
            /**
             * @var SpyProductCategory $product
             */
            $data[] = $product->getIdProductCategory();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getProducts()
    {
        $productList = $this->productCategoryFacade->getProductsByCategory($this->idCategory, $this->locale);

        $data = [];
        foreach ($productList as $product) {
            /**
             * @var SpyProductCategory $product
             */
            $data[$product->getIdProductCategory()] = $product->getName();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $fields = $this->getDefaultFormFields();
        /**
         * @var SpyCategory $category
         */

        $category = $this->categoryQueryContainer->queryCategoryById($this->idCategory)
            ->innerJoinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->findOne()
        ;

        if ($category) {
            $category = $category->toArray();

            $fields = [
                self::PK_CATEGORY => $category[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $category[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $category[self::FK_PARENT_CATEGORY_NODE],
                self::NAME => $category[self::NAME],
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
            self::FK_PARENT_CATEGORY_NODE => null,
            self::NAME => ''
        ];
    }

}
