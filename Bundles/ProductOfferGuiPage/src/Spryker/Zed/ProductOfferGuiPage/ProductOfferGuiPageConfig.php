<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferGuiPageConfig extends AbstractBundleConfig
{
    public const PRODUCT_LIST_TABLE_KEY_NAME = 'name';
    public const PRODUCT_LIST_TABLE_KEY_SKU = 'sku';
    public const PRODUCT_LIST_TABLE_KEY_IMAGE = 'image';
    public const PRODUCT_LIST_TABLE_KEY_STORES = 'stores';
    public const PRODUCT_LIST_TABLE_KEY_STATUS = 'status';
    public const PRODUCT_LIST_TABLE_KEY_HAS_OFFERS = 'hasOffers';

    public const PRODUCT_LIST_TABLE_COL_KEY_KEY = 'key';
    public const PRODUCT_LIST_TABLE_COL_KEY_TITLE = 'title';
    public const PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE = 'is_searchable';
    public const PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE = 'is_sortable';

    public const PRODUCT_LIST_TABLE_FILTER_NAME_IN_CATEGORIES = 'category';
    public const PRODUCT_LIST_TABLE_FILTER_NAME_IN_STORES = 'store';
    public const PRODUCT_LIST_TABLE_FILTER_NAME_IS_ACTIVE = 'isActive';

    public const PRODUCT_LIST_TABLE_APPLICABLE_FILTERS = [
        self::PRODUCT_LIST_TABLE_FILTER_NAME_IN_CATEGORIES,
        self::PRODUCT_LIST_TABLE_FILTER_NAME_IN_STORES,
        self::PRODUCT_LIST_TABLE_FILTER_NAME_IS_ACTIVE,
    ];

    protected const PRODUCT_TABLE_LIST_COLUMNS = [
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_SKU,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Sku',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => true,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => true,
        ],
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_IMAGE,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Image',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => false,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => false,
        ],
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_NAME,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Name',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => true,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => true,
        ],
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_STORES,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Stores',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => false,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => false,
        ],
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_STATUS,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Status',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => false,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => true,
        ],
        [
            self::PRODUCT_LIST_TABLE_COL_KEY_KEY => self::PRODUCT_LIST_TABLE_KEY_HAS_OFFERS,
            self::PRODUCT_LIST_TABLE_COL_KEY_TITLE => 'Offers',
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SEARCHABLE => false,
            self::PRODUCT_LIST_TABLE_COL_KEY_IS_SORTABLE => true,
        ],
    ];

    protected const AVAILABLE_PAGE_SIZES = [10, 25, 50];

    /**
     * @return (string|bool)[]
     */
    public function getProductTableListColumns(): array
    {
        return static::PRODUCT_TABLE_LIST_COLUMNS;
    }

    /**
     * @return int[]
     */
    public function getAvailablePageSizes(): array
    {
        return static::AVAILABLE_PAGE_SIZES;
    }
}
