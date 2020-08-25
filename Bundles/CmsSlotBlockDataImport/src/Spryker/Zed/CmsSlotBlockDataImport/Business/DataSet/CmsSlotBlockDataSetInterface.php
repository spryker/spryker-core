<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet;

interface CmsSlotBlockDataSetInterface
{
    public const COL_SLOT_ID = 'slot_id';
    public const COL_SLOT_KEY = 'slot_key';
    public const COL_BLOCK_KEY = 'block_key';
    public const COL_BLOCK_ID = 'block_id';
    public const COL_SLOT_TEMPLATE_PATH = 'template_path';
    public const COL_SLOT_TEMPLATE_ID = 'template_id';
    public const COL_POSITION = 'position';
    public const COL_CONDITIONS_ARRAY = 'conditions_array';
    public const COL_CONDITIONS_PRODUCT_CATEGORY_ALL = 'conditions.productCategory.all';
    public const COL_CONDITIONS_PRODUCT_CATEGORY_SKUS = 'conditions.productCategory.skus';
    public const COL_CONDITIONS_PRODUCT_CATEGORY_KEYS = 'conditions.productCategory.category_key';
    public const COL_CONDITIONS_CATEGORY_ALL = 'conditions.category.all';
    public const COL_CONDITIONS_CATEGORY_KEYS = 'conditions.category.category_key';
    public const COL_CONDITIONS_CMS_PAGE_ALL = 'conditions.cms_page.all';
    public const COL_CONDITIONS_CMS_PAGE_KEYS = 'conditions.cms_page.page_key';
}
