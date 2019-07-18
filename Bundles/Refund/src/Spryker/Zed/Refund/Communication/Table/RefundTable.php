<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Table;

use Orm\Zed\Refund\Persistence\Map\SpyRefundTableMap;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyInterface;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;

class RefundTable extends AbstractTable
{
    public const ACTIONS = 'Actions';
    public const SPY_SALES_ORDER = 'SpySalesOrder';
    public const COL_CURRENCY_ISO_CODE = 'spy_sales_order.currency_iso_code';

    /**
     * @var \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface
     */
    protected $refundQueryContainer;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $dateFormatter;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface $refundQueryContainer
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $dateFormatter
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyInterface $moneyFacade
     */
    public function __construct(
        RefundQueryContainerInterface $refundQueryContainer,
        UtilDateTimeServiceInterface $dateFormatter,
        RefundToMoneyInterface $moneyFacade
    ) {
        $this->refundQueryContainer = $refundQueryContainer;
        $this->dateFormatter = $dateFormatter;
        $this->moneyFacade = $moneyFacade;
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
            SpyRefundTableMap::COL_COMMENT => 'Comment',
        ]);

        $config->setSortable([
            SpyRefundTableMap::COL_ID_REFUND,
            SpyRefundTableMap::COL_FK_SALES_ORDER,
            SpyRefundTableMap::COL_CREATED_AT,
            SpyRefundTableMap::COL_AMOUNT,
            SpyRefundTableMap::COL_COMMENT,
        ]);

        $config->setSearchable([
            SpyRefundTableMap::COL_ID_REFUND,
            SpyRefundTableMap::COL_FK_SALES_ORDER,
            SpyRefundTableMap::COL_CREATED_AT,
            SpyRefundTableMap::COL_AMOUNT,
            SpyRefundTableMap::COL_COMMENT,
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

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

        $queryResults = $this->runQuery($refundQuery, $config, false);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyRefundTableMap::COL_ID_REFUND => $item[SpyRefundTableMap::COL_ID_REFUND],
                SpyRefundTableMap::COL_FK_SALES_ORDER => $item[SpyRefundTableMap::COL_FK_SALES_ORDER],
                SpyRefundTableMap::COL_CREATED_AT => $this->formatDate($item[SpyRefundTableMap::COL_CREATED_AT]),
                SpyRefundTableMap::COL_AMOUNT => $this->formatAmount(
                    $item[SpyRefundTableMap::COL_AMOUNT],
                    true,
                    $this->findCurrencyIsoCode($item)
                ),
                SpyRefundTableMap::COL_COMMENT => $item[SpyRefundTableMap::COL_COMMENT],
            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string|null
     */
    protected function findCurrencyIsoCode(array $item)
    {
        if (isset($item[static::SPY_SALES_ORDER]) && isset($item[static::SPY_SALES_ORDER][static::COL_CURRENCY_ISO_CODE])) {
            return $item[static::SPY_SALES_ORDER][static::COL_CURRENCY_ISO_CODE];
        }

        return null;
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     * @param string|null $currencyIsoCode
     *
     * @return string
     */
    protected function formatAmount($value, $includeSymbol = true, $currencyIsoCode = null)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value, $currencyIsoCode);
        if ($includeSymbol) {
            return $this->moneyFacade->formatWithSymbol($moneyTransfer);
        }

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param string $date
     *
     * @return bool|string
     */
    protected function formatDate($date)
    {
        return $this->dateFormatter->formatDateTime($date);
    }
}
