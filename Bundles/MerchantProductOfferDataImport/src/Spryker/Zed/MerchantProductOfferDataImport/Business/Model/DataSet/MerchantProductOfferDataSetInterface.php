<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet;

interface MerchantProductOfferDataSetInterface
{
    public const STORE_NAME = 'store_name';
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const CONCRETE_SKU = 'concrete_sku';
    public const MERCHANT_REFERENCE = 'merchant_reference';
    public const MERCHANT_SKU = 'merchant_sku';
    public const IS_ACTIVE = 'is_active';
    public const APPROVAL_STATUS = 'approval_status';
    public const ID_MERCHANT = 'id_merchant';
    public const ID_PRODUCT_OFFER = 'id_product_offer';
    public const ID_STORE = 'id_store';

    public const DEFAULT_APPROVAL_STATUS = 'denied';
}
