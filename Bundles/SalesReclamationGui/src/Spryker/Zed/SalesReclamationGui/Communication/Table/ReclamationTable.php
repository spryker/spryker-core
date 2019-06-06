<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Table;

use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface;

class ReclamationTable extends AbstractTable
{
    public const COL_ACTIONS = 'COL_ACTIONS';

    protected const PARAM_ID_RECLAMATION = 'id-reclamation';

    protected const URL_RECLAMATION_DETAIL = '/sales-reclamation-gui/detail';
    protected const URL_RECLAMATION_CLOSE = '/sales-reclamation-gui/detail/close';

    /**
     * @var \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    protected $salesReclamationQuery;

    /**
     * @var \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface
     */
    protected $dateTimeService;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery $salesReclamationQuery
     * @param \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface $dateTimeService
     */
    public function __construct(
        SpySalesReclamationQuery $salesReclamationQuery,
        SalesReclamationGuiToUtilDateTimeServiceInterface $dateTimeService
    ) {
        $this->salesReclamationQuery = $salesReclamationQuery;
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader($this->getHeaderFields());

        $config->setSearchable([
            SpySalesReclamationTableMap::COL_CUSTOMER_NAME,
        ]);

        $config->addRawColumn(SpySalesReclamationTableMap::COL_IS_OPEN);
        $config->addRawColumn(static::COL_ACTIONS);

        return $config;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            SpySalesReclamationTableMap::COL_ID_SALES_RECLAMATION => '#',
            SpySalesReclamationTableMap::COL_CREATED_AT => 'Created',
            SpySalesReclamationTableMap::COL_CUSTOMER_NAME => 'Customer',
            SpySalesReclamationTableMap::COL_CUSTOMER_EMAIL => 'Email',
            SpySalesReclamationTableMap::COL_IS_OPEN => 'State',
            SpySalesReclamationTableMap::COL_FK_SALES_ORDER => 'Order id',
            static::COL_ACTIONS => 'Actions',
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->salesReclamationQuery, $config);

        return $this->formatQueryData($queryResults);
    }

    /**
     * @param array $queryResults
     *
     * @return array
     */
    protected function formatQueryData(array $queryResults)
    {
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpySalesReclamationTableMap::COL_ID_SALES_RECLAMATION => $item[SpySalesReclamationTableMap::COL_ID_SALES_RECLAMATION],
                SpySalesReclamationTableMap::COL_CREATED_AT => $this->dateTimeService->formatDateTime(
                    $item[SpySalesReclamationTableMap::COL_CREATED_AT]
                ),
                SpySalesReclamationTableMap::COL_CUSTOMER_NAME => $item[SpySalesReclamationTableMap::COL_CUSTOMER_NAME],
                SpySalesReclamationTableMap::COL_CUSTOMER_EMAIL => $item[SpySalesReclamationTableMap::COL_CUSTOMER_EMAIL],
                SpySalesReclamationTableMap::COL_IS_OPEN => $this->createStateLabel(
                    $item[SpySalesReclamationTableMap::COL_IS_OPEN]
                ),
                SpySalesReclamationTableMap::COL_FK_SALES_ORDER => $item[SpySalesReclamationTableMap::COL_FK_SALES_ORDER],
                static::COL_ACTIONS => $this->createActions($item),
            ];
        }

        return $results;
    }

    /**
     * @param bool $isOpen
     *
     * @return string
     */
    protected function createStateLabel(bool $isOpen): string
    {
        if ($isOpen) {
            return $this->generateLabel('Open', 'label-success');
        }

        return $this->generateLabel('Closed', 'label-danger');
    }

    /**
     * @param string[] $item
     *
     * @return string
     */
    protected function createActions(array $item): string
    {
        $idReclamation = $item[SpySalesReclamationTableMap::COL_ID_SALES_RECLAMATION];
        $buttons = [];

        $buttons[] = $this->createViewAction((int)$idReclamation);

        if ($item[SpySalesReclamationTableMap::COL_IS_OPEN]) {
            $buttons[] = $this->createCloseAction((int)$idReclamation);
        }

        return implode(' ', $buttons);
    }

    /**
     * @param int $idReclamation
     *
     * @return string
     */
    protected function createViewAction(int $idReclamation): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_RECLAMATION_DETAIL, [
                static::PARAM_ID_RECLAMATION => $idReclamation,
            ]),
            'View'
        );
    }

    /**
     * @param int $idReclamation
     *
     * @return string
     */
    protected function createCloseAction(int $idReclamation): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_RECLAMATION_CLOSE, [
                static::PARAM_ID_RECLAMATION => $idReclamation,
            ]),
            'Close',
            [
                'class' => 'btn-remove',
                'icon' => 'fa-close',
            ]
        );
    }
}
