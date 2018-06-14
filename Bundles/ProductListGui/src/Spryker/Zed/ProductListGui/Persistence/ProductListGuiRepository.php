<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListGui\Persistence\ProductListGuiPersistenceFactory getFactory()
 */
class ProductListGuiRepository extends AbstractRepository implements ProductListGuiRepositoryInterface
{
    /** @see \Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap::COL_FK_CATEGORY */
    public const COLUMN_CATEGORY = 'fk_category';
    /** @see \Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap::COL_NAME */
    public const COLUMN_NAME = 'name';

    /**
     * @api
     *
     * @module Category
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[] [<category id> => <category name in english locale>]
     */
    public function getAllCategoriesNames(LocaleTransfer $localeTransfer): array
    {
        $categoryAttributes = $this->getFactory()
            ->getCategoryAttributeQuery()
            ->select([
                static::COLUMN_CATEGORY,
                static::COLUMN_NAME,
            ])
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->find();

        $categoryNames = [];
        foreach ($categoryAttributes as $categoryAttribute) {
            $idCategory = $categoryAttribute[static::COLUMN_CATEGORY];
            $categoryNames[$idCategory] = $categoryAttribute[static::COLUMN_NAME];
        }

        return $categoryNames;
    }
}
