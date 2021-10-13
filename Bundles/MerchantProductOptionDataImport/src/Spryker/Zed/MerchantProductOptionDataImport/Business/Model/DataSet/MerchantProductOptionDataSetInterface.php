<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business\Model\DataSet;

interface MerchantProductOptionDataSetInterface
{
    /**
     * @var string
     */
    public const PRODUCT_OPTION_GROUP_KEY = 'product_option_group_key';
    /**
     * @var string
     */
    public const ID_PRODUCT_OPTION_GROUP = 'id_product_option_group';
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const APPROVAL_STATUS = 'approval_status';
    /**
     * @var string
     */
    public const MERCHANT_SKU = 'merchant_sku';
}
