<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Table;

use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface;

class ReclamationTable extends AbstractTable
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface
     */
    protected $dateTimeService;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface $dateTimeService
     */
    public function __construct(
        SalesReclamationQueryContainerInterface $queryContainer,
        SalesReclamationToUtilDateTimeServiceInterface $dateTimeService
    ) {
        $this->queryContainer = $queryContainer;
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
            SpySalesReclamationTableMap::COL_FK_SALES_ORDER => 'Order id',
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->queryContainer->queryReclamations();
        $queryResults = $this->runQuery($query, $config);

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
                SpySalesReclamationTableMap::COL_FK_SALES_ORDER => $item[SpySalesReclamationTableMap::COL_FK_SALES_ORDER],
            ];
        }

        return $results;
    }
}
