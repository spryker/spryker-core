<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormAdd;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormEdit;

class CategoryFormAddDataProvider extends AbstractCategoryFormDataProvider
{

    /**
     * @param int $idParentNode
     * @param int|null $idCategory
     *
     * @return array
     */
    public function getData($idParentNode, $idCategory = null)
    {
        $formData = [];
        $fields = $this->getDefaultFormFields($idParentNode);

        if ($idCategory !== null) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity */
            $categoryEntity = $this->categoryQueryContainer
                ->queryCategoryById($idCategory)
                ->innerJoinNode()
                ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE)
                ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, CategoryFormAdd::FIELD_PK_CATEGORY_NODE)
                ->findOne();

            if ($categoryEntity) {
                $categoryEntity = $categoryEntity->toArray();

                $formData = [
                    self::ID_CATEGORY => $categoryEntity[self::ID_CATEGORY],
                    CategoryFormAdd::FIELD_PK_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_PK_CATEGORY_NODE],
                    CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE],
                    CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE],
                    CategoryFormAdd::LOCALIZED_ATTRIBUTES => $this->getAttributes($idCategory)
                ];
            }
        }

        return array_merge($formData, $fields);
    }

    /**
     * @param int|null $idParentNode
     *
     * @return array
     */
    protected function getDefaultFormFields($idParentNode = null)
    {
        return [
            self::ID_CATEGORY => null,
            CategoryFormAdd::FIELD_PK_CATEGORY_NODE => null,
            CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $idParentNode,
            CategoryFormEdit::LOCALIZED_ATTRIBUTES => $this->getAttributesDefaultFields()
        ];
    }

}
