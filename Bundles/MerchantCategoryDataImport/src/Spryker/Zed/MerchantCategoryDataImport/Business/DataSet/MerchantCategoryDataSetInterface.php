<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCategoryDataImport\Business\DataSet;

interface MerchantCategoryDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const CATEGORY_KEY = 'category_key';

    /**
     * @var string
     */
    public const FK_MERCHANT = 'fk_merchant';
    /**
     * @var string
     */
    public const ID_CATEGORY = 'id_category';
}
