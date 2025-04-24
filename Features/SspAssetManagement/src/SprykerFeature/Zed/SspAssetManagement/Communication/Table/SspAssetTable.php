<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Table;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFormDataProviderInterface;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig;

class SspAssetTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const BASE_URL = '/ssp-asset-management/index/table';

    /**
     * @var string
     */
    public const PARAM_ID_SSP_ASSET = 'id-ssp-asset';

    /**
     * @var string
     */
    protected const URL_SSP_ASSET_DETAIL = '/ssp-asset-management/detail';

    /**
     * @var string
     */
    protected const URL_SSP_ASSET_UPDATE = '/ssp-asset-management/update';

    /**
     * @var string
     */
    protected const BUTTON_VIEW = 'View';

    /**
     * @var string
     */
    protected const BUTTON_UPDATE = 'Edit';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_IMAGE = 'image';

    /**
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery $sspAssetQuery
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \Generated\Shared\Transfer\SspAssetConditionsTransfer $sspAssetConditionsTransfer
     * @param \SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFormDataProviderInterface $sspAssetFormDataProvider
     * @param \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig $sspAssetManagementConfig
     */
    public function __construct(
        protected SpySspAssetQuery $sspAssetQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected SspAssetConditionsTransfer $sspAssetConditionsTransfer,
        protected SspAssetFormDataProviderInterface $sspAssetFormDataProvider,
        protected SspAssetManagementConfig $sspAssetManagementConfig
    ) {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            '/table',
            $this->getRequest()->query->all(),
        );
        $config->setUrl($url->build());

        $config = $this->setHeader($config);

        $config->setSearchable([
            SpySspAssetTableMap::COL_REFERENCE,
            SpySspAssetTableMap::COL_NAME,
            SpySspAssetTableMap::COL_SERIAL_NUMBER,
            SpySspAssetTableMap::COL_STATUS,
        ]);

        $config->setSortable([
            SpySspAssetTableMap::COL_REFERENCE,
            SpySspAssetTableMap::COL_NAME,
            SpySspAssetTableMap::COL_SERIAL_NUMBER,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_ACTIONS,
        ]);

        $config->setDefaultSortField(SpySspAssetTableMap::COL_REFERENCE, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpySspAssetTableMap::COL_REFERENCE => 'Reference',
            static::COL_IMAGE => 'Image',
            SpySspAssetTableMap::COL_NAME => 'Asset Name',
            SpySspAssetTableMap::COL_SERIAL_NUMBER => 'Serial Number',
            SpySspAssetTableMap::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Action',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    protected function prepareQuery(): SpySspAssetQuery
    {
        if ($this->sspAssetConditionsTransfer->getStatus()) {
            $this->sspAssetQuery->filterByStatus($this->sspAssetConditionsTransfer->getStatus());
        }

        return $this->sspAssetQuery;
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
            SpySspAssetTableMap::COL_REFERENCE => $item[SpySspAssetTableMap::COL_REFERENCE],
            static::COL_IMAGE => isset($item[SpySspAssetTableMap::COL_FK_IMAGE_FILE]) ? sprintf('<img src="%s" width="100px">', $this->sspAssetFormDataProvider->getAssetImageUrl(
                (new SspAssetTransfer())->setImage(new FileTransfer())
                ->setReference($item[SpySspAssetTableMap::COL_REFERENCE]),
            )) : '',
            SpySspAssetTableMap::COL_NAME => $item[SpySspAssetTableMap::COL_NAME],
            SpySspAssetTableMap::COL_SERIAL_NUMBER => $item[SpySspAssetTableMap::COL_SERIAL_NUMBER],
            SpySspAssetTableMap::COL_STATUS => $this->sspAssetManagementConfig->getAssetStatuses()[$item[SpySspAssetTableMap::COL_STATUS]] ?? 'N/A',
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    /**
     * @param array<string> $sspAsset
     *
     * @return string
     */
    protected function buildLinks(array $sspAsset): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_SSP_ASSET_DETAIL, [static::PARAM_ID_SSP_ASSET => $sspAsset[SpySspAssetTableMap::COL_ID_SSP_ASSET]]),
            static::BUTTON_VIEW,
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_SSP_ASSET_UPDATE, [static::PARAM_ID_SSP_ASSET => $sspAsset[SpySspAssetTableMap::COL_ID_SSP_ASSET]]),
            static::BUTTON_UPDATE,
        );

        return implode(' ', $buttons);
    }
}
