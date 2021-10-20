<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet;

interface CmsSlotBlockDataSetInterface
{
    /**
     * @var string
     */
    public const COL_SLOT_ID = 'slot_id';

    /**
     * @var string
     */
    public const COL_SLOT_KEY = 'slot_key';

    /**
     * @var string
     */
    public const COL_BLOCK_KEY = 'block_key';

    /**
     * @var string
     */
    public const COL_BLOCK_ID = 'block_id';

    /**
     * @var string
     */
    public const COL_SLOT_TEMPLATE_PATH = 'template_path';

    /**
     * @var string
     */
    public const COL_SLOT_TEMPLATE_ID = 'template_id';

    /**
     * @var string
     */
    public const COL_POSITION = 'position';

    /**
     * @var string
     */
    public const COL_CONDITIONS_ARRAY = 'conditions_array';

    /**
     * @var string
     */
    public const COL_CONDITIONS_PRODUCT_CATEGORY_ALL = 'conditions.productCategory.all';

    /**
     * @var string
     */
    public const COL_CONDITIONS_PRODUCT_CATEGORY_SKUS = 'conditions.productCategory.skus';

    /**
     * @var string
     */
    public const COL_CONDITIONS_PRODUCT_CATEGORY_KEYS = 'conditions.productCategory.category_key';

    /**
     * @var string
     */
    public const COL_CONDITIONS_CATEGORY_ALL = 'conditions.category.all';

    /**
     * @var string
     */
    public const COL_CONDITIONS_CATEGORY_KEYS = 'conditions.category.category_key';

    /**
     * @var string
     */
    public const COL_CONDITIONS_CMS_PAGE_ALL = 'conditions.cms_page.all';

    /**
     * @var string
     */
    public const COL_CONDITIONS_CMS_PAGE_KEYS = 'conditions.cms_page.page_key';
}
