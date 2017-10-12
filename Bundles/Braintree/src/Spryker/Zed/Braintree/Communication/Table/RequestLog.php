<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Table;

use Orm\Zed\Braintree\Persistence\Map\SpyPaymentBraintreeTransactionRequestLogTableMap;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RequestLog extends AbstractTable
{
    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery
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
        SpyPaymentBraintreeTransactionRequestLogTableMap::COL_ID_PAYMENT_BRAINTREE_TRANSACTION_REQUEST_LOG,
        SpyPaymentBraintreeTransactionRequestLogTableMap::COL_FK_PAYMENT_BRAINTREE,
        SpyPaymentBraintreeTransactionRequestLogTableMap::COL_CREATED_AT,
        SpyPaymentBraintreeTransactionRequestLogTableMap::COL_UPDATED_AT,
    ];

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery $requestLogQuery
     * @param int $idPayment
     */
    public function __construct(SpyPaymentBraintreeTransactionRequestLogQuery $requestLogQuery, $idPayment)
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
            SpyPaymentBraintreeTransactionRequestLogTableMap::COL_TRANSACTION_ID,
        ]);
        $config->setUrl('request-log-table?id-payment=' . $this->idPayment);

        return $config;
    }

    /**
     * @return array
     */
    private function getHeaderFields()
    {
        $fieldNames = SpyPaymentBraintreeTransactionRequestLogTableMap::getFieldNames(
            SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_COLNAME
        );
        $headerFields = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, static::$excludeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentBraintreeTransactionRequestLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_COLNAME,
                SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_FIELDNAME
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
        $fieldNames = SpyPaymentBraintreeTransactionRequestLogTableMap::getFieldNames(
            SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_COLNAME
        );
        $resultArray = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, static::$excludeFields)) {
                continue;
            }

            $translatedFieldName = SpyPaymentBraintreeTransactionRequestLogTableMap::translateFieldName(
                $fieldName,
                SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_COLNAME,
                SpyPaymentBraintreeTransactionRequestLogTableMap::TYPE_FIELDNAME
            );

            $resultArray[$translatedFieldName] = $logItem[$fieldName];
        }

        return $resultArray;
    }
}
