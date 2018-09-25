<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataSet;

interface SalesOrderThresholdDataSetInterface
{
    public const COLUMN_STORE = 'store';
    public const COLUMN_CURRENCY = 'currency';
    public const COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY = 'threshold_type_key';
    public const COLUMN_THRESHOLD = 'threshold';
    public const COLUMN_FEE = 'fee';
    public const COLUMN_MESSAGE_GLOSSARY_KEY = 'message_glossary_key';
}
