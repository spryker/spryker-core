<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataSet;

interface MerchantCommissionAmountDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_MERCHANT_COMMISSION_KEY = 'merchant_commission_key';

    /**
     * @var string
     */
    public const COLUMN_CURRENCY = 'currency';

    /**
     * @var string
     */
    public const COLUMN_VALUE_NET = 'value_net';

    /**
     * @var string
     */
    public const COLUMN_VALUE_GROSS = 'value_gross';

    /**
     * @var string
     */
    public const ID_CURRENCY = 'id_currency';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\Common\MerchantCommissionKeyToIdMerchantCommissionDataImportStep::ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    public const ID_MERCHANT_COMMISSION = 'id_merchant_commission';
}
