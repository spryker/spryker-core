<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet;

interface CategoryStoreDataSetInterface
{
    public const COLUMN_CATEGORY_KEY = 'category_key';
    public const COLUMN_INCLUDED_STORE_NAME = 'included_store_names';
    public const COLUMN_EXCLUDED_STORE_NAME = 'excluded_store_names';

    public const ID_CATEGORY = 'id_category';
    public const INCLUDED_STORE_IDS = 'included_store_ids';
    public const EXCLUDED_STORE_IDS = 'excluded_store_ids';
}
