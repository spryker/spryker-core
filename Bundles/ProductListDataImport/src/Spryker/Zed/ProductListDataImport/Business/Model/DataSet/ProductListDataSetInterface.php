<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\DataSet;

interface ProductListDataSetInterface
{
    /**
     * @var string
     */
    public const PRODUCT_LIST_KEY = 'product_list_key';

    /**
     * @var string
     */
    public const PRODUCT_LIST_NAME = 'name';

    /**
     * @var string
     */
    public const PRODUCT_LIST_TYPE = 'type';

    /**
     * @var string
     */
    public const CATEGORY_KEY = 'category_key';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const ID_PRODUCT_LIST = 'id_product_list';

    /**
     * @var string
     */
    public const ID_CATEGORY = 'id_category';

    /**
     * @var string
     */
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
}
