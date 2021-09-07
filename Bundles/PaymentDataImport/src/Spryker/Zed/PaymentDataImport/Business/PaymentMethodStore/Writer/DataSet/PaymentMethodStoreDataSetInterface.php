<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\DataSet;

interface PaymentMethodStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COL_PAYMENT_METHOD_KEY = 'payment_method_key';
    /**
     * @var string
     */
    public const COL_STORE = 'store';
    /**
     * @var string
     */
    public const COL_ID_PAYMENT_METHOD = 'fk_payment_method';
    /**
     * @var string
     */
    public const COL_ID_STORE = 'fk_store';
}
