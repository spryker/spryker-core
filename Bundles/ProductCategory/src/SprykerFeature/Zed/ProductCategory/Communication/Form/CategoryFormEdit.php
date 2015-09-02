<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form;

use SprykerFeature\Zed\Category\Persistence\Propel\Base\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormEdit extends CategoryFormAdd
{

    const ATTRIBUTE_META_TITLE = 'meta_title';
    const ATTRIBUTE_META_DESCRIPTION = 'meta_description';
    const ATTRIBUTE_META_KEYWORDS = 'meta_keywords';
    const ATTRIBUTE_CATEGORY_IMAGE_NAME = 'category_image_nam';
    const ATTRIBUTE_CATEGORY_ROBOTS = 'robots';
    const ATTRIBUTE_CATEGORY_CANONICAL = 'canonical';
    const ATTRIBUTE_CATEGORY_ALTERNATE_TAG = 'alternate_tag';
    
    const CATEGORY_IS_ACTIVE = 'is_active';
    const CATEGORY_IS_IN_MENU = 'is_in_menu';

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
            ->addText(self::ATTRIBUTE_META_TITLE, [
                'label' => 'Meta Title',
            ])
            ->addTextarea(self::ATTRIBUTE_META_DESCRIPTION, [
                'label' => 'Meta Description',
            ])
            ->addTextarea(self::ATTRIBUTE_META_KEYWORDS, [
                'label' => 'Meta Keywords',
            ])
            ->addTextarea(self::ATTRIBUTE_CATEGORY_ROBOTS, [
                'label' => 'Robots tag',
            ])
            ->addTextarea(self::ATTRIBUTE_CATEGORY_CANONICAL, [
                'label' => 'Canonical',
            ])
            ->addTextarea(self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG, [
                'label' => 'Alternate tag',
            ])
            ->addCheckbox(self::CATEGORY_IS_ACTIVE, [
                'label' => 'Active',
            ])
            ->addCheckbox(self::CATEGORY_IS_IN_MENU, [
                'label' => 'Show in Menu',
            ])
            ->addSelect2ComboBox(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Parent',
                'choices' => $this->getCategories(),
                'constraints' => [
                    new NotBlank(),
                ],
                'multiple' => true,
            ])
            ->addSelect2ComboBox('products', [
                'label' => 'Products',
                'choices' => $this->getProducts(),
                'constraints' => [
                    new NotBlank(),
                ],
                'multiple' => true,
                'data' => $this->getAssignedProducts()
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ->addHidden('products_to_be_assigned', [
                'attr' => [
                    'id' => 'products_to_be_assigned'
                ]
            ])
            ->addHidden('products_to_be_de_assigned', [
                'attr' => [
                    'id' => 'products_to_be_de_assigned'
                ]
            ])
        ;
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
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_TITLE, self::ATTRIBUTE_META_TITLE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_DESCRIPTION, self::ATTRIBUTE_META_DESCRIPTION)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_KEYWORDS, self::ATTRIBUTE_META_KEYWORDS)
            ->withColumn(SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME, self::ATTRIBUTE_CATEGORY_IMAGE_NAME)
            ->withColumn(SpyCategoryAttributeTableMap::COL_ROBOTS, self::ATTRIBUTE_CATEGORY_ROBOTS)
            ->withColumn(SpyCategoryAttributeTableMap::COL_CANONICAL, self::ATTRIBUTE_CATEGORY_CANONICAL)
            ->withColumn(SpyCategoryAttributeTableMap::COL_ALTERNATE_TAG, self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->findOne()
        ;
        
        if ($category) {
            $nodeList = $this->categoryQueryContainer->queryNodesByCategoryId($this->idCategory)
                ->find()
            ;
            
            $nodeIds = [];
            foreach ($nodeList as $node) {
                $nodeIds[] = $node->getFkParentCategoryNode();
            }
            
            $category = $category->toArray();

            $fields = [
                self::PK_CATEGORY => $category[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $category[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $nodeIds,
                self::NAME => $category[self::NAME],
                //meta
                self::ATTRIBUTE_META_TITLE => $category[self::ATTRIBUTE_META_TITLE],
                self::ATTRIBUTE_META_DESCRIPTION => $category[self::ATTRIBUTE_META_DESCRIPTION],
                self::ATTRIBUTE_META_KEYWORDS => $category[self::ATTRIBUTE_META_KEYWORDS],
                //image
                self::ATTRIBUTE_CATEGORY_IMAGE_NAME => $category[self::ATTRIBUTE_CATEGORY_IMAGE_NAME],
                //seo
                self::ATTRIBUTE_CATEGORY_ROBOTS => $category[self::ATTRIBUTE_CATEGORY_ROBOTS],
                self::ATTRIBUTE_CATEGORY_CANONICAL => $category[self::ATTRIBUTE_CATEGORY_CANONICAL],
                self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG => $category[self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG],
                //category
                self::CATEGORY_IS_ACTIVE => $category[self::CATEGORY_IS_ACTIVE],
                self::CATEGORY_IS_IN_MENU => $category[self::CATEGORY_IS_IN_MENU],
            ];
        }

        return $fields;
    }


    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        $fields = parent::getDefaultFormFields();

        return array_merge($fields, [
            //meta
            self::ATTRIBUTE_META_TITLE => '',
            self::ATTRIBUTE_META_DESCRIPTION => '',
            self::ATTRIBUTE_META_KEYWORDS => '',
            //image
            self::ATTRIBUTE_CATEGORY_IMAGE_NAME => '',
            //seo
            self::ATTRIBUTE_CATEGORY_ROBOTS => '',
            self::ATTRIBUTE_CATEGORY_CANONICAL => '',
            self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG => '',
            //category
            self::CATEGORY_IS_ACTIVE => '',
            self::CATEGORY_IS_IN_MENU => '',
        ]);
    }

}
