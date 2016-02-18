<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Table;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Refund\Business\RefundFacade;
use Orm\Zed\Refund\Persistence\Map\SpyRefundTableMap;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

class RefundTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const URL_REFUND_DETAILS = '/refund/details/';
    const PARAM_ID_REFUND = 'id-refund';

    /**
     * @var \Spryker\Zed\Refund\Business\RefundFacade
     */
    protected $refundFacade;

    /**
     * @var \Spryker\Shared\Library\DateFormatter
     */
    protected $dateFormatter;

    /**
     * @param \Orm\Zed\Refund\Persistence\SpyRefundQuery $refundQuery
     * @param \Spryker\Zed\Refund\Business\RefundFacade $refundFacade
     * @param \Spryker\Shared\Library\DateFormatter $dateFormatter
     */
    public function __construct(SpyRefundQuery $refundQuery, RefundFacade $refundFacade, DateFormatter $dateFormatter)
    {
        $this->refundQuery = $refundQuery;
        $this->refundFacade = $refundFacade;
        $this->dateFormatter = $dateFormatter;
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
            SpyRefundTableMap::COL_CREATED_AT => 'Refund date',
            SpyRefundTableMap::COL_AMOUNT => 'Amount',
            SpyRefundTableMap::COL_COMMENT => 'Comment',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->setSortable([
            SpyRefundTableMap::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            SpyRefundTableMap::COL_ID_REFUND,
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
        $query = $this->refundQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyRefundTableMap::COL_ID_REFUND => $item[SpyRefundTableMap::COL_ID_REFUND],
                SpyRefundTableMap::COL_CREATED_AT => $this->formatDate($item[SpyRefundTableMap::COL_CREATED_AT]),
                SpyRefundTableMap::COL_AMOUNT => $this->formatAmount($item[SpyRefundTableMap::COL_AMOUNT]),
                SpyRefundTableMap::COL_COMMENT => $item[SpyRefundTableMap::COL_COMMENT],
                self::ACTIONS => implode(' ', $this->createActionUrls($item)),
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
        $currencyManager = CurrencyManager::getInstance();
        $value = $currencyManager->convertCentToDecimal($value);

        return $currencyManager->format($value, $includeSymbol);
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

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionUrls(array $item)
    {
        $urls = [];
        $urls[] = $this->generateViewButton(
            Url::generate(self::URL_REFUND_DETAILS, [
                self::PARAM_ID_REFUND => $item[SpyRefundTableMap::COL_ID_REFUND],
            ]),
            'View'
        );

        return $urls;
    }

}
