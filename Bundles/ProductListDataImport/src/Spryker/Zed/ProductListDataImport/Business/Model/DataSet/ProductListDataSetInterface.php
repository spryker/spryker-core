<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\DataSet;

interface ProductListDataSetInterface
{
    public const PRODUCT_LIST_KEY = 'product_list_key';
    public const PRODUCT_LIST_NAME = 'name';
    public const PRODUCT_LIST_TYPE = 'type';

    public const CATEGORY_KEY = 'category_key';
    public const CONCRETE_SKU = 'concrete_sku';

    public const ID_PRODUCT_LIST = 'id_product_list';
    public const ID_CATEGORY = 'id_category';
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
}
