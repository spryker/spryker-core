<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Table;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\SalesReturn\Persistence\Map\SpySalesReturnItemTableMap;
use Orm\Zed\SalesReturn\Persistence\Map\SpySalesReturnTableMap;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;

class OrderReturnTable extends AbstractTable
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';
    protected const PARAM_ID_RETURN = 'id-return';

    protected const URL_RETURN_DETAIL = '/sales-return-gui/return/detail';
    protected const BUTTON_VIEW = 'View';

    protected const BASE_URL = '/sales-return-gui/sales/';

    protected const COL_RETURN_REFERENCE = 'return_reference';
    protected const COL_ITEMS = 'items';
    protected const COL_REMUNERATION_TOTAL = 'remuneration_total';
    protected const COL_CURRENCY = 'currency';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(OrderTransfer $orderTransfer, SalesReturnGuiToMoneyFacadeInterface $moneyFacade)
    {
        $this->orderTransfer = $orderTransfer;
        $this->moneyFacade = $moneyFacade;

        $this->baseUrl = static::BASE_URL;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl('table?' . self::PARAM_ID_SALES_ORDER . '=' . $this->orderTransfer->getIdSalesOrder());

        $config->setHeader([
            static::COL_RETURN_REFERENCE => 'Return reference',
            static::COL_ITEMS => 'Items',
            static::COL_REMUNERATION_TOTAL => 'Remuneration total',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_RETURN_REFERENCE,
        ]);

        $config->setSortable([
            static::COL_RETURN_REFERENCE,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $results = [];

        $returnResults = $this->runQuery(
            $this->prepareQuery(),
            $config
        );

        foreach ($returnResults as $return) {
            $results[] = [
                static::COL_RETURN_REFERENCE => $return[SpySalesReturnTableMap::COL_RETURN_REFERENCE],
                static::COL_ITEMS => $return[static::COL_ITEMS],
                static::COL_REMUNERATION_TOTAL => $this->getRemunerationTotal($return),
                static::COL_ACTIONS => $this->buildLinks($return),
            ];
        }

        return $results;
    }

    /**
     * @module Sales
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected function prepareQuery(): SpySalesReturnQuery
    {
        $this->orderTransfer->requireIdSalesOrder();

        return SpySalesReturnQuery::create()
            ->leftJoinSpySalesReturnItem()
            ->useSpySalesReturnItemQuery()
                ->leftJoinSpySalesOrderItem()
                ->useSpySalesOrderItemQuery()
                    ->filterByFkSalesOrder($this->orderTransfer->getIdSalesOrder())
                    ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_REMUNERATION_AMOUNT . ')', static::COL_REMUNERATION_TOTAL)
                    ->useOrderQuery()
                        ->withColumn(SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE, static::COL_CURRENCY)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(
                'COUNT(' . SpySalesReturnItemTableMap::COL_ID_SALES_RETURN_ITEM . ')',
                static::COL_ITEMS
            )
            ->groupByIdSalesReturn();
    }

    /**
     * @param string[] $return
     *
     * @return string
     */
    protected function getRemunerationTotal(array $return)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger(
            (int)$return[static::COL_REMUNERATION_TOTAL] ?? 0,
            $return[static::COL_CURRENCY]
        );

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param string[] $return
     *
     * @return string
     */
    protected function buildLinks(array $return): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_RETURN_DETAIL, [static::PARAM_ID_RETURN => $return[SpySalesReturnTableMap::COL_ID_SALES_RETURN]]),
            static::BUTTON_VIEW
        );

        return implode(' ', $buttons);
    }
}
