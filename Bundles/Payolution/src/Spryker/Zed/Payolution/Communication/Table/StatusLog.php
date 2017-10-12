<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication\Table;

use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTransactionStatusLogTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StatusLog extends AbstractTable
{
    const FIELD_DETAILS = 'FIELD_DETAILS';

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
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
        SpyPaymentPayolutionTransactionStatusLogTableMap::COL_IDENTIFICATION_TRANSACTIONID,
        SpyPaymentPayolutionTransactionStatusLogTableMap::COL_PROCESSING_RETURN,
        SpyPaymentPayolutionTransactionStatusLogTableMap::COL_PROCESSING_STATUS_CODE,
        SpyPaymentPayolutionTransactionStatusLogTableMap::COL_PROCESSING_REASON_CODE,
    ];

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery $statusLogQuery
     * @param int $idPayment
     */
    public function __construct(SpyPaymentPayolutionTransactionStatusLogQuery $statusLogQuery, $idPayment)
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
            SpyPaymentPayolutionTransactionStatusLogTableMap::COL_IDENTIFICATION_TRANSACTIONID,
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
        foreach (self::$includeFields as $fieldName) {
            $translatedFieldName = SpyPaymentPayolutionTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $fieldLabel = str_replace(['processing_', 'identification_'], '', $translatedFieldName);
            $headerFields[$translatedFieldName] = $fieldLabel;
        }

        $headerFields[self::FIELD_DETAILS] = 'Details';

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
        foreach (self::$includeFields as $fieldName) {
            $translatedFieldName = SpyPaymentPayolutionTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $resultArray[$translatedFieldName] = $logItem[$fieldName];
        }

        $resultArray[self::FIELD_DETAILS] = $this->getDetailsFieldValue($logItem);

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
        $fieldNames = SpyPaymentPayolutionTransactionStatusLogTableMap::getFieldNames(
            SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_COLNAME
        );
        $tupleRows = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, self::$includeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentPayolutionTransactionStatusLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_COLNAME,
                SpyPaymentPayolutionTransactionStatusLogTableMap::TYPE_FIELDNAME
            );

            $tupleRows[] = sprintf('%s:&nbsp;%s', $translatedFieldName, $logItem[$fieldName]);
        }

        $detailsText = implode('<br />', $tupleRows);

        return $detailsText;
    }
}
