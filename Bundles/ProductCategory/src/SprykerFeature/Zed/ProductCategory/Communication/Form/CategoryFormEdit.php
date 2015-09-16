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
    const CATEGORY_IS_CLICKABLE = 'is_clickable';
    const CATEGORY_NODE_IS_MAIN = 'is_main';
    
    const EXTRA_PARENTS = 'extra_parents';

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
            ->addCheckbox(self::CATEGORY_IS_CLICKABLE, [
                'label' => 'Clickable',
            ])
            ->addSelect2ComboBox(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Parent',
                'choices' => $this->getCategories(),
                'constraints' => [
                    new NotBlank(),
                ],
                'multiple' => false,
            ])
            ->addSelect2ComboBox(self::EXTRA_PARENTS, [
                'label' => 'Extra Categories',
                'choices' => $this->getCategories(),
                'multiple' => true,
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ->addHidden(self::FK_NODE_CATEGORY)
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
            ->addHidden('product_order', [
                'attr' => [
                    'id' => 'product_order'
                ]
            ])
            ->addHidden('product_category_preconfig', [
                'attr' => [
                    'id' => 'product_category_preconfig'
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
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, self::FK_NODE_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, self::CATEGORY_NODE_IS_MAIN)
            ->findOne()
        ;
        
        if ($category) {
            $category = $category->toArray();
            
            $nodeList = $this->categoryQueryContainer->queryNodesByCategoryId($this->idCategory)
                ->where(
                    SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE . ' <> ?',
                    $category[self::PK_CATEGORY_NODE]
                )
                ->find()
            ;
            
            $nodeIds = [];
            foreach ($nodeList as $node) {
                $nodeIds[] = $node->getFkParentCategoryNode();
            }
            
            

            $fields = [
                self::PK_CATEGORY => $category[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $category[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $category[self::FK_PARENT_CATEGORY_NODE],
                self::FK_NODE_CATEGORY => $category[self::FK_NODE_CATEGORY],
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
                self::CATEGORY_NODE_IS_MAIN => $category[self::CATEGORY_NODE_IS_MAIN],

                self::EXTRA_PARENTS => $nodeIds,
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
            self::PK_CATEGORY => null,
            self::PK_CATEGORY_NODE => null,
            self::FK_PARENT_CATEGORY_NODE => null,
            self::FK_NODE_CATEGORY => null,
            self::NAME => null,
            //meta
            self::ATTRIBUTE_META_TITLE => null,
            self::ATTRIBUTE_META_DESCRIPTION => null,
            self::ATTRIBUTE_META_KEYWORDS => null,
            //image
            self::ATTRIBUTE_CATEGORY_IMAGE_NAME => null,
            //seo
            self::ATTRIBUTE_CATEGORY_ROBOTS => null,
            self::ATTRIBUTE_CATEGORY_CANONICAL => null,
            self::ATTRIBUTE_CATEGORY_ALTERNATE_TAG => null,
            //category
            self::CATEGORY_IS_ACTIVE => null,
            self::CATEGORY_IS_IN_MENU => null,
            self::CATEGORY_NODE_IS_MAIN => null,

            self::EXTRA_PARENTS => null,
        ]);
    }

}
