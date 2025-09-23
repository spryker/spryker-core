<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table;

use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class AttachedAssetsTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_REFERENCE = 'reference';

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_SERIAL_NUMBER = 'serial_number';

    /**
     * @var string
     */
    protected const COL_STATUS = 'status';

    public function __construct(
        protected SspModelTransfer $sspModelTransfer,
        protected SpySspAssetQuery $sspAssetQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            'attached-asset-table',
            ['id-ssp-model' => $this->sspModelTransfer->getIdSspModel()],
        );
        $config->setUrl($url->build());

        $config = $this->setHeader($config);

        $config->setSearchable([
            static::COL_REFERENCE,
            static::COL_NAME,
            static::COL_SERIAL_NUMBER,
            static::COL_STATUS,
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
        ]);

        $config->setSortable([
            static::COL_REFERENCE,
            static::COL_NAME,
            static::COL_SERIAL_NUMBER,
            static::COL_STATUS,
        ]);

        $config->setDefaultSortField(static::COL_REFERENCE, TableConfiguration::SORT_ASC);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            static::COL_REFERENCE => 'Asset Reference',
            static::COL_NAME => 'Asset Name',
            static::COL_SERIAL_NUMBER => 'Asset Serial Number',
            static::COL_STATUS => 'Status',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    protected function prepareQuery(): SpySspAssetQuery
    {
        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery $query */
        $query = $this->sspAssetQuery
            ->useSpySspAssetToSspModelQuery()
                ->filterByFkSspModel($this->sspModelTransfer->getIdSspModel())
            ->endUse();

        return $query;
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
     * @return array<int|string, mixed>
     */
    protected function formatRow(array $item): array
    {
        return [
            static::COL_REFERENCE => $item[SpySspAssetTableMap::COL_REFERENCE] ?? '',
            static::COL_NAME => $item[SpySspAssetTableMap::COL_NAME] ?? '',
            static::COL_SERIAL_NUMBER => $item[SpySspAssetTableMap::COL_SERIAL_NUMBER] ?? '',
            static::COL_STATUS => $this->generateLabel(
                $item[SpySspAssetTableMap::COL_STATUS] ?? '',
                $this->selfServicePortalConfig->getAssetStatusClassMap()[$item[SpySspAssetTableMap::COL_STATUS]] ?? null,
            ),
        ];
    }
}
