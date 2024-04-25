<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataSet;

interface MerchantCommissionMerchantDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var string
     */
    public const COLUMN_MERCHANT_COMMISSION_KEY = 'merchant_commission_key';

    /**
     * @var string
     */
    public const ID_MERCHANT = 'id_merchant';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\Common\MerchantCommissionKeyToIdMerchantCommissionDataImportStep::ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    public const ID_MERCHANT_COMMISSION = 'id_merchant_commission';
}
