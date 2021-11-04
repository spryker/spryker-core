<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet;

interface MerchantProductOfferDataSetInterface
{
    /**
     * @var string
     */
    public const STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var string
     */
    public const MERCHANT_SKU = 'merchant_sku';

    /**
     * @var string
     */
    public const IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const APPROVAL_STATUS = 'approval_status';

    /**
     * @var string
     */
    public const ID_MERCHANT = 'id_merchant';

    /**
     * @var string
     */
    public const ID_PRODUCT_OFFER = 'id_product_offer';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';
}
