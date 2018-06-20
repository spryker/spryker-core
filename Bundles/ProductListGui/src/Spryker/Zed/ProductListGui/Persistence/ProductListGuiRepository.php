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
    public const COLUMN_ID_CATEGORY = 'fk_category';
    /** @see \Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap::COL_NAME */
    public const COLUMN_CATEGORY_NAME = 'name';
    /** @see \Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT */
    public const COLUMN_ID_PRODUCT = 'fk_product';
    /** @see \Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap::COL_NAME */
    public const COLUMN_PRODUCT_NAME = 'name';

    /**
     * @api
     *
     * @module Category
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[] [<category id> => <category name in english locale>]
     */
    public function getAllCategoryNames(LocaleTransfer $localeTransfer): array
    {
        $categoryAttributes = $this->getFactory()
            ->getCategoryAttributeQuery()
            ->select([
                static::COLUMN_ID_CATEGORY,
                static::COLUMN_CATEGORY_NAME,
            ])
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->find();

        $categoryNames = [];
        foreach ($categoryAttributes as $categoryAttribute) {
            $idCategory = $categoryAttribute[static::COLUMN_ID_CATEGORY];
            $categoryNames[$idCategory] = $categoryAttribute[static::COLUMN_CATEGORY_NAME];
        }

        return $categoryNames;
    }

    /**
     * @api
     *
     * @module Product
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[] [<product id> => <product name in english locale>]
     */
    public function getAllProductNames(LocaleTransfer $localeTransfer): array
    {
        $productAttributes = $this->getFactory()
            ->getProductAttributeQuery()
            ->select([
                static::COLUMN_ID_PRODUCT,
                static::COLUMN_PRODUCT_NAME,
            ])
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->find();

        $productNames = [];
        foreach ($productAttributes as $productAttribute) {
            $idProduct = $productAttribute[static::COLUMN_ID_PRODUCT];
            $productNames[$idProduct] = $productAttribute[static::COLUMN_PRODUCT_NAME];
        }

        return $productNames;
    }
}
