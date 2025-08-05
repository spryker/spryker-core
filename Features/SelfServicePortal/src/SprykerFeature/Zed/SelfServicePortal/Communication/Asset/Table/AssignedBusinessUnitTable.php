<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table;

use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToCompanyBusinessUnitTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedBusinessUnitTable extends AbstractTable
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListAssignedBusinessUnitAssetController::indexAction()
     *
     * @var string
     */
    protected const BASE_URL = '/self-service-portal/list-assigned-business-unit-asset';

    /**
     * @var string
     */
    public const PARAM_ID_SSP_ASSET = 'id-ssp-asset';

    public function __construct(
        protected SspAssetTransfer $sspAssetTransfer,
        protected SpySspAssetToCompanyBusinessUnitQuery $sspAssetToCompanyBusinessUnitQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->baseUrl = static::BASE_URL;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(sprintf('table?%s=%s', static::PARAM_ID_SSP_ASSET, $this->sspAssetTransfer->getIdSspAssetOrFail()));

        $config = $this->setHeader($config);

        $config->setSearchable([
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
        ]);

        $config->setRawColumns([
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
        ]);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpyCompanyTableMap::COL_NAME => 'Company',
            SpyCompanyBusinessUnitTableMap::COL_NAME => 'Business unit',
            SpySspAssetToCompanyBusinessUnitTableMap::COL_CREATED_AT => 'Date attached',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    protected function prepareQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        $this->sspAssetToCompanyBusinessUnitQuery
            ->filterByFkSspAsset($this->sspAssetTransfer->getIdSspAsset())
            ->joinWithSpyCompanyBusinessUnit()
            ->useSpyCompanyBusinessUnitQuery()
                ->joinWithCompany()
            ->endUse()
            ->select([
                SpyCompanyTableMap::COL_NAME,
                SpyCompanyBusinessUnitTableMap::COL_NAME,
                SpySspAssetToCompanyBusinessUnitTableMap::COL_CREATED_AT,
            ]);

        return $this->sspAssetToCompanyBusinessUnitQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<mixed>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = $this->formatRow($item);
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $item): array
    {
        $rowData = [
            SpyCompanyTableMap::COL_NAME => $item[SpyCompanyTableMap::COL_NAME],
            SpyCompanyBusinessUnitTableMap::COL_NAME => $item[SpyCompanyBusinessUnitTableMap::COL_NAME],
            SpySspAssetToCompanyBusinessUnitTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySspAssetToCompanyBusinessUnitTableMap::COL_CREATED_AT]),
        ];

        return $rowData;
    }
}
