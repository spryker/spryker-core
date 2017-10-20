<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Table;

use Orm\Zed\Braintree\Persistence\Map\SpyPaymentBraintreeTransactionStatusLogTableMap;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StatusLog extends AbstractTable
{
    const FIELD_DETAILS = 'FIELD_DETAILS';

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    private $statusLogQuery;

    /**
     * @var int
     */
    private $idPayment;

    /**
     * @var string[]
     */
    private static $includeFields = [
        SpyPaymentBraintreeTransactionStatusLogTableMap::COL_TRANSACTION_ID,
        SpyPaymentBraintreeTransactionStatusLogTableMap::COL_TRANSACTION_CODE,
        SpyPaymentBraintreeTransactionStatusLogTableMap::COL_TRANSACTION_STATUS,
        SpyPaymentBraintreeTransactionStatusLogTableMap::COL_TRANSACTION_TYPE,
    ];

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery $statusLogQuery
     * @param int $idPayment
     */
    public function __construct(SpyPaymentBraintreeTransactionStatusLogQuery $statusLogQuery, $idPayment)
    {
        $this->statusLogQuery = $statusLogQuery;
        $this->idPayment = $idPayment;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader($this->getHeaderFields());
        $config->setSortable([
            SpyPaymentBraintreeTransactionStatusLogTableMap::COL_TRANSACTION_ID,
        ]);
        $config->setUrl('status-log-table?id-payment=' . $this->idPayment);

        return $config;
    }

    /**
     * @return array
     */
    private function getHeaderFields()
    {
        $headerFields = [];
        foreach (static::$includeFields as $fieldName) {
            $translatedFieldName = SpyPaymentBraintreeTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $fieldLabel = str_replace(['processing_', 'identification_'], '', $translatedFieldName);
            $headerFields[$translatedFieldName] = $fieldLabel;
        }

        $headerFields[static::FIELD_DETAILS] = 'Details';

        return $headerFields;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $logItems = $this->runQuery($this->statusLogQuery, $config);
        $results = [];
        foreach ($logItems as $logItem) {
            $results[] = $this->getFieldMatchedResultArrayFromLogItem($logItem);
        }

        return $results;
    }

    /**
     * Returns an array that matches field values from $logItem with the table's
     * fields so that it renders correctly assigned field.
     *
     * @param array $logItem
     *
     * @return array
     */
    private function getFieldMatchedResultArrayFromLogItem(array $logItem)
    {
        $resultArray = [];
        foreach (static::$includeFields as $fieldName) {
            $translatedFieldName = SpyPaymentBraintreeTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $resultArray[$translatedFieldName] = $logItem[$fieldName];
        }

        $resultArray[static::FIELD_DETAILS] = $this->getDetailsFieldValue($logItem);

        return $resultArray;
    }

    /**
     * Dumps all remaining fields (and their values) into a single string representation.
     *
     * @param array $logItem
     *
     * @return string
     */
    private function getDetailsFieldValue(array $logItem)
    {
        $fieldNames = SpyPaymentBraintreeTransactionStatusLogTableMap::getFieldNames(
            SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_COLNAME
        );
        $tupleRows = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, static::$includeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentBraintreeTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentBraintreeTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $tupleRows[] = sprintf('%s:&nbsp;%s', $translatedFieldName, $logItem[$fieldName]);
        }

        $detailsText = implode('<br />', $tupleRows);

        return $detailsText;
    }
}
