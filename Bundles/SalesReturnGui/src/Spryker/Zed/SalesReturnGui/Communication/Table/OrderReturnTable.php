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
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;

class OrderReturnTable extends AbstractTable
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::PARAM_ID_ORDER
     */
    public const PARAM_ID_ORDER = 'id-order';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\AbstractReturnController::PARAM_ID_RETURN
     */
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\DetailController::indexAction()
     */
    protected const URL_RETURN_DETAIL = '/sales-return-gui/detail';
    protected const BUTTON_VIEW = 'View';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\SalesController
     */
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
     * @var \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected $salesReturnQuery;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface $moneyFacade
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery $salesReturnQuery
     */
    public function __construct(
        OrderTransfer $orderTransfer,
        SalesReturnGuiToMoneyFacadeInterface $moneyFacade,
        SpySalesReturnQuery $salesReturnQuery
    ) {
        $this->orderTransfer = $orderTransfer;
        $this->moneyFacade = $moneyFacade;
        $this->salesReturnQuery = $salesReturnQuery;

        $this->baseUrl = static::BASE_URL;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(sprintf('table?%s=%s', static::PARAM_ID_ORDER, $this->orderTransfer->getIdSalesOrder()));

        $config->setHeader([
            static::COL_RETURN_REFERENCE => 'Return Reference',
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
        $config->setDefaultSortField(static::COL_RETURN_REFERENCE, TableConfiguration::SORT_DESC);

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

        $salesReturnIds = (clone $this->salesReturnQuery)
            ->useSpySalesReturnItemQuery(null, Criteria::LEFT_JOIN)
                ->useSpySalesOrderItemQuery(null, Criteria::LEFT_JOIN)
                    ->filterByFkSalesOrder($this->orderTransfer->getIdSalesOrder())
                ->endUse()
            ->endUse()
            ->groupByIdSalesReturn()
            ->select([SpySalesReturnTableMap::COL_ID_SALES_RETURN])
            ->find()
            ->toArray();

        return $this->salesReturnQuery
            ->filterByIdSalesReturn_In($salesReturnIds)
            ->useSpySalesReturnItemQuery(null, Criteria::LEFT_JOIN)
                ->useSpySalesOrderItemQuery(null, Criteria::LEFT_JOIN)
                    ->withColumn(sprintf('SUM(%s)', SpySalesOrderItemTableMap::COL_REMUNERATION_AMOUNT), static::COL_REMUNERATION_TOTAL)
                    ->useOrderQuery(null, Criteria::LEFT_JOIN)
                        ->withColumn(SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE, static::COL_CURRENCY)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(sprintf('COUNT(%s)', SpySalesReturnItemTableMap::COL_ID_SALES_RETURN_ITEM), static::COL_ITEMS)
            ->groupByIdSalesReturn();
    }

    /**
     * @param string[] $return
     *
     * @return string
     */
    protected function getRemunerationTotal(array $return): string
    {
        $moneyTransfer = $this->moneyFacade->fromInteger(
            (int)$return[static::COL_REMUNERATION_TOTAL],
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
