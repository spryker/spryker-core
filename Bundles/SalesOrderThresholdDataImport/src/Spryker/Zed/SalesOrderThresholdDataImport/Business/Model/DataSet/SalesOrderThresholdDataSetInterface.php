<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataSet;

interface SalesOrderThresholdDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE = 'store';
    /**
     * @var string
     */
    public const COLUMN_CURRENCY = 'currency';
    /**
     * @var string
     */
    public const COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY = 'threshold_type_key';
    /**
     * @var string
     */
    public const COLUMN_THRESHOLD = 'threshold';
    /**
     * @var string
     */
    public const COLUMN_FEE = 'fee';
    /**
     * @var string
     */
    public const COLUMN_MESSAGE_GLOSSARY_KEY = 'message_glossary_key';
}
