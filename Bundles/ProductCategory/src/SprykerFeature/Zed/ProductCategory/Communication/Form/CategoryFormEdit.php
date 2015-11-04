<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;

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
     * @return self
     */
    protected function buildFormFields()
    {
        $categoriesWithPath = $this->getCategoriesWithPaths();

        return $this->addText(self::NAME, [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
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
                'choices' => $categoriesWithPath,
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
                'multiple' => false,
            ])
            ->addSelect2ComboBox(self::EXTRA_PARENTS, [
                'label' => 'Additional Parents',
                'choices' => $categoriesWithPath,
                'multiple' => true,
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ->addHidden(self::FK_NODE_CATEGORY)
            ->addHidden('products_to_be_assigned', [
                'attr' => [
                    'id' => 'products_to_be_assigned',
                ],
            ])
            ->addHidden('products_to_be_de_assigned', [
                'attr' => [
                    'id' => 'products_to_be_de_assigned',
                ],
            ])
            ->addHidden('product_order', [
                'attr' => [
                    'id' => 'product_order',
                ],
            ])
            ->addHidden('product_category_preconfig', [
                'attr' => [
                    'id' => 'product_category_preconfig',
                ],
            ])
        ;
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
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_TITLE, self::ATTRIBUTE_META_TITLE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_DESCRIPTION, self::ATTRIBUTE_META_DESCRIPTION)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_KEYWORDS, self::ATTRIBUTE_META_KEYWORDS)
            ->withColumn(SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME, self::ATTRIBUTE_CATEGORY_IMAGE_NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, self::FK_NODE_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, self::CATEGORY_NODE_IS_MAIN)
            ->findOne()
        ;

        if ($categoryEntity) {
            $categoryEntity = $categoryEntity->toArray();

            $nodeEntityList = $this->categoryQueryContainer
                ->queryNotMainNodesByCategoryId($this->idCategory)
                ->where(
                    SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE . ' <> ?',
                    $categoryEntity[self::PK_CATEGORY_NODE]
                )
                ->find()
            ;

            $nodeIds = [];
            foreach ($nodeEntityList as $nodeEntity) {
                $nodeIds[] = $nodeEntity->getFkParentCategoryNode();
            }

            $fields = [
                self::PK_CATEGORY => $categoryEntity[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $categoryEntity[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $categoryEntity[self::FK_PARENT_CATEGORY_NODE],
                self::FK_NODE_CATEGORY => $categoryEntity[self::FK_NODE_CATEGORY],
                self::NAME => $categoryEntity[self::NAME],
                //meta
                self::ATTRIBUTE_META_TITLE => $categoryEntity[self::ATTRIBUTE_META_TITLE],
                self::ATTRIBUTE_META_DESCRIPTION => $categoryEntity[self::ATTRIBUTE_META_DESCRIPTION],
                self::ATTRIBUTE_META_KEYWORDS => $categoryEntity[self::ATTRIBUTE_META_KEYWORDS],
                //image
                self::ATTRIBUTE_CATEGORY_IMAGE_NAME => $categoryEntity[self::ATTRIBUTE_CATEGORY_IMAGE_NAME],
                //category
                self::CATEGORY_IS_ACTIVE => $categoryEntity[self::CATEGORY_IS_ACTIVE],
                self::CATEGORY_IS_IN_MENU => $categoryEntity[self::CATEGORY_IS_IN_MENU],
                self::CATEGORY_IS_CLICKABLE => $categoryEntity[self::CATEGORY_IS_CLICKABLE],
                self::CATEGORY_NODE_IS_MAIN => $categoryEntity[self::CATEGORY_NODE_IS_MAIN],

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
            //category
            self::CATEGORY_IS_ACTIVE => null,
            self::CATEGORY_IS_IN_MENU => null,
            self::CATEGORY_IS_CLICKABLE => null,
            self::CATEGORY_NODE_IS_MAIN => null,

            self::EXTRA_PARENTS => null,
        ]);
    }

}
