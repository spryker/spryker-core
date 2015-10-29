<?php

namespace SprykerFeature\Zed\Refund\Communication\Table;

use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Shared\Library\DateFormatter;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use Orm\Zed\Refund\Persistence\Map\SpyRefundTableMap;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

class RefundTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var RefundFacade
     */
    protected $refundFacade;

    /**
     * @var DateFormatter
     */
    protected $dateFormatter;

    /**
     * @param SpyRefundQuery $refundQuery
     * @param RefundFacade $refundFacade
     * @param DateFormatter $dateFormatter
     */
    public function __construct(SpyRefundQuery $refundQuery, RefundFacade $refundFacade, DateFormatter $dateFormatter)
    {
        $this->refundQuery = $refundQuery;
        $this->refundFacade = $refundFacade;
        $this->dateFormatter = $dateFormatter;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
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
     * @param TableConfiguration $config
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
                self::ACTIONS => sprintf(
                    '<a class="btn btn-primary" href="/refund/details/?id-refund=%d">View</a>',
                    $item[SpyRefundTableMap::COL_ID_REFUND]
                ),
            ];
        }

        return $results;
    }

    /**
     * @param int  $value
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

}
