<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOmsDataImport\Business\DataSet;

interface MerchantOmsProcessDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const FK_STATE_MACHINE_PROCESS = 'fk_state_machine_process';
    /**
     * @var string
     */
    public const MERCHANT_OMS_PROCESS_NAME = 'merchant_oms_process_name';
}
