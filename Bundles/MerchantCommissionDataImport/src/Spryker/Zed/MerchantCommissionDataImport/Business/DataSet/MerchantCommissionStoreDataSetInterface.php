<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataSet;

interface MerchantCommissionStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_MERCHANT_COMMISSION_KEY = 'merchant_commission_key';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\Common\MerchantCommissionKeyToIdMerchantCommissionDataImportStep::ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    public const ID_MERCHANT_COMMISSION = 'id_merchant_commission';
}
