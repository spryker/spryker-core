<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\DataSet;

interface MerchantProductDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var string
     */
    public const FK_MERCHANT = 'fk_merchant';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_SKU = 'sku';

    /**
     * @var string
     */
    public const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
}
