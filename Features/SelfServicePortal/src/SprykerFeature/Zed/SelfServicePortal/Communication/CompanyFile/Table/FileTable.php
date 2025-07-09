<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Generated\Shared\Transfer\FileAttachmentTableCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;

class FileTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const HEADER_FILE_NAME = 'File Name';

    /**
     * @var string
     */
    protected const HEADER_FILE_REFERENCE = 'Reference';

    /**
     * @var string
     */
    protected const HEADER_SIZE = 'Size';

    /**
     * @var string
     */
    protected const HEADER_TYPE = 'Type';

    /**
     * @var string
     */
    protected const HEADER_DATE_UPLOADED = 'Date Uploaded';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const COL_SIZE = 'size';

    /**
     * @var string
     */
    protected const COL_TYPE = 'type';

    /**
     * @var string
     */
    protected const COL_ID_FILE_INFO = 'id_file_info';

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    /**
     * @var int
     */
    protected const NUMBER_OF_DECIMALS = 2;

    /**
     * @var string
     */
    protected const URL_TABLE = '/table';

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $fileQuery
     * @param \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatterInterface $fileSizeFormatter
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface $timeZoneFormatter
     * @param \Generated\Shared\Transfer\FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
     */
    public function __construct(
        protected SpyFileQuery $fileQuery,
        protected FileSizeFormatterInterface $fileSizeFormatter,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected TimeZoneFormatterInterface $timeZoneFormatter,
        protected FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
    ) {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyFileTableMap::COL_ID_FILE => static::HEADER_FILE_REFERENCE,
            SpyFileTableMap::COL_FILE_NAME => static::HEADER_FILE_NAME,
            static::COL_CREATED_AT => static::HEADER_DATE_UPLOADED,
            static::COL_SIZE => static::HEADER_SIZE,
            static::COL_TYPE => static::HEADER_TYPE,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setSortable([
            SpyFileTableMap::COL_FILE_NAME,
            SpyFileTableMap::COL_ID_FILE,
            static::COL_CREATED_AT,
            static::COL_SIZE,
            static::COL_TYPE,
        ]);

        $config->setSearchable([
            SpyFileTableMap::COL_FILE_NAME,
            SpyFileTableMap::COL_FILE_REFERENCE,
            SpyFileInfoTableMap::COL_EXTENSION,
            SpyFileInfoTableMap::COL_CREATED_AT,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setUrl($this->getTableUrl());

        $config->setDefaultSortField(SpyFileTableMap::COL_ID_FILE, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @return string
     */
    protected function getTableUrl(): string
    {
        return Url::generate(
            static::URL_TABLE,
            $this->getRequest()->query->all(),
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->prepareTableRow($item);
        }

        return $results;
    }

    /**
     * @param array<mixed> $item
     *
     * @return array<mixed>
     */
    protected function prepareTableRow(array $item): array
    {
        return [
            SpyFileTableMap::COL_ID_FILE => $item[SpyFileTableMap::COL_FILE_REFERENCE],
            SpyFileTableMap::COL_FILE_NAME => $item[SpyFileTableMap::COL_FILE_NAME],
            static::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[static::COL_CREATED_AT]),
            static::COL_SIZE => $this->fileSizeFormatter->formatFileSize($item[static::COL_SIZE], static::NUMBER_OF_DECIMALS),
            static::COL_TYPE => $item[static::COL_TYPE],
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function prepareQuery(): SpyFileQuery
    {
        $fileQuery = $this->fileQuery->innerJoinSpyFileInfo()
            ->withColumn(SpyFileInfoTableMap::COL_SIZE, static::COL_SIZE)
            ->withColumn(SpyFileInfoTableMap::COL_EXTENSION, static::COL_TYPE)
            ->withColumn(SpyFileInfoTableMap::COL_ID_FILE_INFO, static::COL_ID_FILE_INFO)
            ->withColumn(SpyFileInfoTableMap::COL_CREATED_AT, static::COL_CREATED_AT);

        $fileQuery = $this->applyFilters($fileQuery, $this->fileAttachmentTableCriteriaTransfer);

        return $fileQuery;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $fileQuery
     * @param \Generated\Shared\Transfer\FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFilters(
        SpyFileQuery $fileQuery,
        FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
    ): SpyFileQuery {
        if ($fileAttachmentTableCriteriaTransfer->getExtension()) {
            $fileQuery
                ->useSpyFileInfoQuery()
                    ->filterByExtension($this->fileAttachmentTableCriteriaTransfer->getExtension())
                ->endUse();
        }

        if ($fileAttachmentTableCriteriaTransfer->getDateFrom()) {
            $fileQuery
                ->useSpyFileInfoQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentTableCriteriaTransfer->getDateFrom()), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($fileAttachmentTableCriteriaTransfer->getDateTo()) {
            $fileQuery
                ->useSpyFileInfoQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentTableCriteriaTransfer->getDateTo()), Criteria::LESS_THAN)
                ->endUse();
        }

        return $fileQuery;
    }

    /**
     * @param array<mixed> $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(FileAbstractController::URL_PATH_VIEW_FILE, [
                FileAbstractController::REQUEST_PARAM_ID_FILE => $item[SpyFileTableMap::COL_ID_FILE],
            ]),
            'View',
            [
                'data-qa' => 'view-button',
            ],
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(FileAbstractController::URL_PATH_ATTACH_FILE, [
                FileAbstractController::REQUEST_PARAM_ID_FILE => $item[SpyFileTableMap::COL_ID_FILE],
            ]),
            'Attach',
            [
                'data-qa' => 'attach-button',
            ],
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(FileAbstractController::URL_PATH_DELETE_FILE_CONFIRM_DELETE, [
                FileAbstractController::REQUEST_PARAM_ID_FILE => $item[SpyFileTableMap::COL_ID_FILE],
            ]),
            'Delete',
        );

        $buttons[] = $this->generateViewButton(
            Url::generate(FileAbstractController::URL_PATH_FILE_MANAGER_GUI_DOWNLOAD_FILE, [
                FileAbstractController::REQUEST_PARAM_ID_FILE_INFO => $item[static::COL_ID_FILE_INFO],
            ]),
            'Download',
        );

        return implode(' ', $buttons);
    }
}
