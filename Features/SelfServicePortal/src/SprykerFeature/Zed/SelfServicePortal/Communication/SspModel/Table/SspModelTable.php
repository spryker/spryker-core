<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider\ModelImageUrlProvider;

class SspModelTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const PARAM_ID_SSP_MODEL = 'id-ssp-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewModelController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_MODEL_DETAIL = '/self-service-portal/view-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\UpdateModelController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_MODEL_UPDATE = '/self-service-portal/update-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\DeleteModelController::confirmDeleteAction()
     *
     * @var string
     */
    protected const URL_SSP_MODEL_DELETE = '/self-service-portal/delete-model/confirm-delete';

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
    protected const BUTTON_DELETE = 'Delete';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_IMAGE = 'image';

    public function __construct(
        protected SpySspModelQuery $sspModelQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected ModelImageUrlProvider $modelImageUrlProvider
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            '/table',
            $this->getRequest()->query->all(),
        );
        $config->setUrl($url->build());

        $config = $this->setHeader($config);

        $config->setSearchable([
            SpySspModelTableMap::COL_REFERENCE,
            SpySspModelTableMap::COL_NAME,
            SpySspModelTableMap::COL_CODE,
        ]);

        $config->setSortable([
            SpySspModelTableMap::COL_REFERENCE,
            SpySspModelTableMap::COL_NAME,
            SpySspModelTableMap::COL_CODE,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_ACTIONS,
        ]);

        $config->setDefaultSortField(SpySspModelTableMap::COL_REFERENCE, TableConfiguration::SORT_DESC);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpySspModelTableMap::COL_REFERENCE => 'Reference',
            static::COL_IMAGE => 'Image',
            SpySspModelTableMap::COL_NAME => 'Name',
            SpySspModelTableMap::COL_CODE => 'Code',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    protected function prepareQuery(): SpySspModelQuery
    {
        return $this->sspModelQuery
            ->leftJoinWithFile();
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
            static::COL_IMAGE => $this->getImage($item),
            SpySspModelTableMap::COL_REFERENCE => $item[SpySspModelTableMap::COL_REFERENCE] ?? '',
            SpySspModelTableMap::COL_NAME => $item[SpySspModelTableMap::COL_NAME] ?? '',
            SpySspModelTableMap::COL_CODE => $item[SpySspModelTableMap::COL_CODE] ?? '',
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return string
     */
    protected function getImage(array $item): string
    {
        $sspModelTransfer = (new SspModelTransfer())
            ->setReference($item[SpySspModelTableMap::COL_REFERENCE])
            ->setImageUrl($item[SpySspModelTableMap::COL_IMAGE_URL]);

        if ($item[SpySspModelTableMap::COL_FK_IMAGE_FILE]) {
            $sspModelTransfer->setImage(
                (new FileTransfer())
                ->setIdFile($item[SpySspModelTableMap::COL_FK_IMAGE_FILE]),
            );
        }

        $imageUrl = $this->modelImageUrlProvider->getImageUrl($sspModelTransfer);

        return $imageUrl ? sprintf('<img src="%s" width="50" height="50" />', $imageUrl) : '';
    }

    /**
     * @param array<string> $sspModel
     *
     * @return string
     */
    protected function buildLinks(array $sspModel): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_SSP_MODEL_DETAIL, [static::PARAM_ID_SSP_MODEL => $sspModel[SpySspModelTableMap::COL_ID_SSP_MODEL]]),
            static::BUTTON_VIEW,
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_SSP_MODEL_UPDATE, [static::PARAM_ID_SSP_MODEL => $sspModel[SpySspModelTableMap::COL_ID_SSP_MODEL]]),
            static::BUTTON_UPDATE,
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::URL_SSP_MODEL_DELETE, [static::PARAM_ID_SSP_MODEL => $sspModel[SpySspModelTableMap::COL_ID_SSP_MODEL]]),
            static::BUTTON_DELETE,
        );

        return implode(' ', $buttons);
    }
}
