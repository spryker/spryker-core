<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementCategory\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\ProductManagementCategory\Communication\Form\CategoryFormEdit;

class CategoryFormEditDataProvider extends AbstractCategoryFormDataProvider
{

    const CATEGORY_IS_ACTIVE = 'is_active';
    const CATEGORY_IS_IN_MENU = 'is_in_menu';
    const CATEGORY_IS_CLICKABLE = 'is_clickable';
    const CATEGORY_NODE_IS_MAIN = 'is_main';

    const EXTRA_PARENTS = 'extra_parents';

    /**
     * @param int|null $idCategory
     *
     * @return array
     */
    public function getData($idCategory)
    {
        $fields = $this->getDefaultFormFields();

        /** @var \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity */
        $categoryEntity = $this->categoryQueryContainer
            ->queryCategoryById($idCategory)
            ->innerJoinAttribute()
            ->addAnd(SpyCategoryAttributeTableMap::COL_FK_LOCALE, $this->locale->getIdLocale())
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, CategoryFormEdit::FIELD_NAME)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_TITLE, CategoryFormEdit::FIELD_META_TITLE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_DESCRIPTION, CategoryFormEdit::FIELD_META_DESCRIPTION)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_KEYWORDS, CategoryFormEdit::FIELD_META_KEYWORDS)
            ->withColumn(SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME, CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, CategoryFormEdit::FIELD_PK_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, CategoryFormEdit::FIELD_FK_NODE_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, CategoryFormEdit::CATEGORY_NODE_IS_MAIN)
            ->findOne();

        if ($categoryEntity) {
            $categoryEntity = $categoryEntity->toArray();

            $nodeEntityList = $this->categoryQueryContainer
                ->queryNotMainNodesByCategoryId($idCategory)
                ->where(
                    SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE . ' <> ?',
                    $categoryEntity[CategoryFormEdit::FIELD_PK_CATEGORY_NODE]
                )
                ->find();

            $nodeIds = [];
            foreach ($nodeEntityList as $nodeEntity) {
                $nodeIds[] = $nodeEntity->getFkParentCategoryNode();
            }

            $fields = [
                self::PK_CATEGORY => $categoryEntity[self::PK_CATEGORY],
                CategoryFormEdit::FIELD_PK_CATEGORY_NODE => $categoryEntity[CategoryFormEdit::FIELD_PK_CATEGORY_NODE],
                CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE],
                CategoryFormEdit::FIELD_FK_NODE_CATEGORY => $categoryEntity[CategoryFormEdit::FIELD_FK_NODE_CATEGORY],
                CategoryFormEdit::FIELD_NAME => $categoryEntity[CategoryFormEdit::FIELD_NAME],
                CategoryFormEdit::FIELD_CATEGORY_KEY => $categoryEntity[CategoryFormEdit::FIELD_CATEGORY_KEY],
                //meta
                CategoryFormEdit::FIELD_META_TITLE => $categoryEntity[CategoryFormEdit::FIELD_META_TITLE],
                CategoryFormEdit::FIELD_META_DESCRIPTION => $categoryEntity[CategoryFormEdit::FIELD_META_DESCRIPTION],
                CategoryFormEdit::FIELD_META_KEYWORDS => $categoryEntity[CategoryFormEdit::FIELD_META_KEYWORDS],
                //image
                CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME => $categoryEntity[CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME],
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
        return [
            self::PK_CATEGORY => null,
            CategoryFormEdit::FIELD_PK_CATEGORY_NODE => null,
            CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE => null,
            CategoryFormEdit::FIELD_FK_NODE_CATEGORY => null,
            CategoryFormEdit::FIELD_NAME => null,
            //meta
            CategoryFormEdit::FIELD_META_TITLE => null,
            CategoryFormEdit::FIELD_META_DESCRIPTION => null,
            CategoryFormEdit::FIELD_META_KEYWORDS => null,
            //image
            CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME => null,
            //category
            self::CATEGORY_IS_ACTIVE => null,
            self::CATEGORY_IS_IN_MENU => null,
            self::CATEGORY_IS_CLICKABLE => null,
            self::CATEGORY_NODE_IS_MAIN => null,

            self::EXTRA_PARENTS => null,
        ];
    }

}
