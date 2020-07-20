<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\DataSet;

interface MerchantProductDataSetInterface
{
    public const MERCHANT_REFERENCE = 'merchant_reference';
    public const FK_MERCHANT = 'fk_merchant';

    public const PRODUCT_ABSTRACT_SKU = 'sku';
    public const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
}
