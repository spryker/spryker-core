<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Table;

use Orm\Zed\Refund\Persistence\Map\SpyRefundTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;

class RefundTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface
     */
    protected $refundQueryContainer;

    /**
     * @var \Spryker\Shared\Library\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface $refundQueryContainer
     * @param \Spryker\Shared\Library\DateFormatterInterface $dateFormatter
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManager
     */
    public function __construct(RefundQueryContainerInterface $refundQueryContainer, DateFormatterInterface $dateFormatter, CurrencyManagerInterface $currencyManager)
    {
        $this->refundQueryContainer = $refundQueryContainer;
        $this->dateFormatter = $dateFormatter;
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyRefundTableMap::COL_ID_REFUND => 'Refund Id',
            SpyRefundTableMap::COL_FK_SALES_ORDER => 'Sales Order Id',
            SpyRefundTableMap::COL_CREATED_AT => 'Refund date',
            SpyRefundTableMap::COL_AMOUNT => 'Amount',
            SpyRefundTableMap::COL_COMMENT => 'Comment'
        ]);

        $config->setSortable([
            SpyRefundTableMap::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            SpyRefundTableMap::COL_ID_REFUND,
            SpyRefundTableMap::COL_FK_SALES_ORDER,
            SpyRefundTableMap::COL_CREATED_AT,
            SpyRefundTableMap::COL_AMOUNT,
            SpyRefundTableMap::COL_COMMENT,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $refundQuery = $this->refundQueryContainer->queryRefunds();
        $refundQuery->orderByIdRefund(Criteria::DESC);

        $queryResults = $this->runQuery($refundQuery, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyRefundTableMap::COL_ID_REFUND => $item[SpyRefundTableMap::COL_ID_REFUND],
                SpyRefundTableMap::COL_FK_SALES_ORDER => $item[SpyRefundTableMap::COL_FK_SALES_ORDER],
                SpyRefundTableMap::COL_CREATED_AT => $this->formatDate($item[SpyRefundTableMap::COL_CREATED_AT]),
                SpyRefundTableMap::COL_AMOUNT => $this->formatAmount($item[SpyRefundTableMap::COL_AMOUNT]),
                SpyRefundTableMap::COL_COMMENT => $item[SpyRefundTableMap::COL_COMMENT]
            ];
        }

        return $results;
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     *
     * @return string
     */
    protected function formatAmount($value, $includeSymbol = true)
    {
        $value = $this->currencyManager->convertCentToDecimal($value);

        return $this->currencyManager->format($value, $includeSymbol);
    }

    /**
     * @param string $date
     *
     * @return bool|string
     */
    protected function formatDate($date)
    {
        return $this->dateFormatter->dateTime($date);
    }

}
