<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet;

interface MerchantProductOfferDataSetInterface
{
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const CONCRETE_SKU = 'concrete_sku';
    public const MERCHANT_KEY = 'merchant_key';
    public const FK_MERCHANT = 'fk_merchant';
    public const MERCHANT_SKU = 'merchant_sku';
    public const IS_ACTIVE = 'is_active';
    public const APPROVAL_STATUS = 'approval_status';
}
