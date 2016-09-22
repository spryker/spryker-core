<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormEdit;

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
        $formData = $this->getDefaultFormFields();

        /** @var \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity */
        $categoryEntity = $this->categoryQueryContainer
            ->queryCategoryById($idCategory)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, CategoryFormEdit::FIELD_PK_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, CategoryFormEdit::FIELD_FK_NODE_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, CategoryFormEdit::CATEGORY_NODE_IS_MAIN)
            ->withColumn(SpyCategoryNodeTableMap::COL_NODE_ORDER, CategoryFormEdit::CATEGORY_NODE_ORDER)
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

            $foo = $this->getAttributes($idCategory);
            $localizedAttributes = array_merge($this->getAttributesDefaultFields(), $foo);

            $formData = [
                self::ID_CATEGORY => $categoryEntity[self::ID_CATEGORY],
                //node
                CategoryFormEdit::FIELD_PK_CATEGORY_NODE => $categoryEntity[CategoryFormEdit::FIELD_PK_CATEGORY_NODE],
                CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE],
                CategoryFormEdit::FIELD_FK_NODE_CATEGORY => $categoryEntity[CategoryFormEdit::FIELD_FK_NODE_CATEGORY],
                CategoryFormEdit::FIELD_CATEGORY_KEY => $categoryEntity[CategoryFormEdit::FIELD_CATEGORY_KEY],
                CategoryFormEdit::CATEGORY_NODE_ORDER => $categoryEntity[CategoryFormEdit::CATEGORY_NODE_ORDER],
                //category
                self::CATEGORY_IS_ACTIVE => $categoryEntity[self::CATEGORY_IS_ACTIVE],
                self::CATEGORY_IS_IN_MENU => $categoryEntity[self::CATEGORY_IS_IN_MENU],
                self::CATEGORY_IS_CLICKABLE => $categoryEntity[self::CATEGORY_IS_CLICKABLE],
                self::CATEGORY_NODE_IS_MAIN => $categoryEntity[self::CATEGORY_NODE_IS_MAIN],
                //parents
                self::EXTRA_PARENTS => $nodeIds,
                //attributes
                CategoryFormEdit::LOCALIZED_ATTRIBUTES => $localizedAttributes
            ];
        }

        return $formData;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            //node
            self::ID_CATEGORY => null,
            CategoryFormEdit::FIELD_PK_CATEGORY_NODE => null,
            CategoryFormEdit::FIELD_FK_PARENT_CATEGORY_NODE => null,
            CategoryFormEdit::FIELD_FK_NODE_CATEGORY => null,
            //category
            self::CATEGORY_IS_ACTIVE => null,
            self::CATEGORY_IS_IN_MENU => null,
            self::CATEGORY_IS_CLICKABLE => null,
            self::CATEGORY_NODE_IS_MAIN => null,

            self::EXTRA_PARENTS => null,
            CategoryFormEdit::LOCALIZED_ATTRIBUTES => $this->getAttributesDefaultFields()
        ];
    }

}
