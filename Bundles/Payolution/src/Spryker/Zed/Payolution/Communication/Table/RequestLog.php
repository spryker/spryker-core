<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication\Table;

use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTransactionRequestLogTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RequestLog extends AbstractTable
{
    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery
     */
    private $requestLogQuery;

    /**
     * @var int
     */
    private $idPayment;

    /**
     * @var string[]
     */
    private static $excludeFields = [
        SpyPaymentPayolutionTransactionRequestLogTableMap::COL_ID_PAYMENT_PAYOLUTION_TRANSACTION_REQUEST_LOG,
        SpyPaymentPayolutionTransactionRequestLogTableMap::COL_FK_PAYMENT_PAYOLUTION,
        SpyPaymentPayolutionTransactionRequestLogTableMap::COL_CREATED_AT,
        SpyPaymentPayolutionTransactionRequestLogTableMap::COL_UPDATED_AT,
    ];

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery $requestLogQuery
     * @param int $idPayment
     */
    public function __construct(SpyPaymentPayolutionTransactionRequestLogQuery $requestLogQuery, $idPayment)
    {
        $this->requestLogQuery = $requestLogQuery;
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
            SpyPaymentPayolutionTransactionRequestLogTableMap::COL_TRANSACTION_ID,
        ]);
        $config->setUrl('request-log-table?id-payment=' . $this->idPayment);

        return $config;
    }

    /**
     * @return array
     */
    private function getHeaderFields()
    {
        $fieldNames = SpyPaymentPayolutionTransactionRequestLogTableMap::getFieldNames(
            SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_COLNAME
        );
        $headerFields = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, self::$excludeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentPayolutionTransactionRequestLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_COLNAME,
                SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_FIELDNAME
            );

            $headerFields[$translatedFieldName] = $translatedFieldName;
        }

        return $headerFields;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $logItems = $this->runQuery($this->requestLogQuery, $config);
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
        $fieldNames = SpyPaymentPayolutionTransactionRequestLogTableMap::getFieldNames(
            SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_COLNAME
        );
        $resultArray = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, self::$excludeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentPayolutionTransactionRequestLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_COLNAME,
                SpyPaymentPayolutionTransactionRequestLogTableMap::TYPE_FIELDNAME
            );

            $resultArray[$translatedFieldName] = $logItem[$fieldName];
        }

        return $resultArray;
    }
}
