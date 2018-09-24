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
use Spryker\Zed\SalesReclamationGui\SalesReclamationGuiConfig;

class ReclamationTable extends AbstractTable
{
    public const COL_ACTIONS = 'COL_ACTIONS';
    public const URL_RECLAMATION_DETAIL = '/sales-reclamation-gui/detail';
    public const URL_RECLAMATION_CLOSE = '/sales-reclamation-gui/detail/close';

    /**
     * @var \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    protected $reclamationQuery;

    /**
     * @var \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface
     */
    protected $dateTimeService;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery $reclamationQuery
     * @param \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface $dateTimeService
     */
    public function __construct(
        SpySalesReclamationQuery $reclamationQuery,
        SalesReclamationGuiToUtilDateTimeServiceInterface $dateTimeService
    ) {
        $this->reclamationQuery = $reclamationQuery;
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

        $config->addRawColumn(SpySalesReclamationTableMap::COL_STATE);
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
            SpySalesReclamationTableMap::COL_STATE => 'State',
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
        $queryResults = $this->runQuery($this->reclamationQuery, $config);

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
                SpySalesReclamationTableMap::COL_STATE => $this->createStateLabel(
                    $item[SpySalesReclamationTableMap::COL_STATE]
                ),
                SpySalesReclamationTableMap::COL_FK_SALES_ORDER => $item[SpySalesReclamationTableMap::COL_FK_SALES_ORDER],
                static::COL_ACTIONS => $this->createActions($item),
            ];
        }

        return $results;
    }

    /**
     * @param string $state
     *
     * @return string
     */
    protected function createStateLabel(string $state): string
    {
        $stateLabel = '';
        switch ($state) {
            case SpySalesReclamationTableMap::COL_STATE_OPEN:
                $stateLabel = '<span class="label label-success" title="Active">Open</span>';
                break;
            case SpySalesReclamationTableMap::COL_STATE_CLOSE:
                $stateLabel = '<span class="label label-danger" title="Deactivated">Closed</span>';
                break;
        }

        return $stateLabel;
    }

    /**
     * @param string[] $item
     *
     * @return string
     */
    protected function createActions(array $item): string
    {
        $idReclamation = $item[SpySalesReclamationTableMap::COL_ID_SALES_RECLAMATION];
        $isClosed = $item[SpySalesReclamationTableMap::COL_STATE] === SpySalesReclamationTableMap::COL_STATE_CLOSE;
        $buttons = [];

        $buttons[] = $this->createViesAction((int)$idReclamation);

        if (!$isClosed) {
            $buttons[] = $this->createCloseAction((int)$idReclamation);
        }

        return implode(' ', $buttons);
    }

    /**
     * @param int $idReclamation
     *
     * @return string
     */
    protected function createViesAction(int $idReclamation): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_RECLAMATION_DETAIL, [
                SalesReclamationGuiConfig::PARAM_ID_RECLAMATION => $idReclamation,
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
                SalesReclamationGuiConfig::PARAM_ID_RECLAMATION => $idReclamation,
            ]),
            'Close',
            [
                'class' => 'btn-remove',
                'icon' => 'fa-close',
            ]
        );
    }
}
