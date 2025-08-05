<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyBusinessUnitFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyUserFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Adapter\Pdo\PdoAdapter;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\StatementWrapper;
use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Propel;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UnlinkFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;

class ViewFileDetailTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const HEADER_BUSINESS_ENTITY_NAME = 'Business entity name';

    /**
     * @var string
     */
    protected const HEADER_BUSINESS_ENTITY_TYPE = 'Business entity type';

    /**
     * @var string
     */
    protected const HEADER_DATE_ATTACHED = 'Date Attached';

    /**
     * @var string
     */
    protected const COL_ATTACHED_AT = 'attached_at';

    /**
     * @var string
     */
    protected const COL_ENTITY_NAME = 'entity_name';

    /**
     * @var string
     */
    protected const COL_ENTITY_TYPE = 'entity_type';

    /**
     * @var string
     */
    protected const COL_ID_FILE = 'id_file';

    /**
     * @var string
     */
    protected const COL_ENTITY_ID = 'entity_id';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const COL_ENTITY_TYPE_COMPANY = '\'company\'';

    /**
     * @var string
     */
    protected const COL_ENTITY_TYPE_COMPANY_BUSINESS_UNIT = '\'company_business_unit\'';

    /**
     * @var string
     */
    protected const COL_ENTITY_TYPE_COMPANY_USER = '\'company_user\'';

    /**
     * @var string
     */
    protected const COL_ENTITY_TYPE_ASSET = '\'ssp_asset\'';

    /**
     * @var string
     */
    protected const SORTABLE_COLUMN = 'column';

    /**
     * @var string
     */
    protected const SORTABLE_DIRECTION = 'dir';

    public function __construct(
        protected SpyFileQuery $fileQuery,
        protected int $idFile,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected TimeZoneFormatterInterface $timeZoneFormatter,
        protected FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(Url::generate(
            '/table',
            $this->getRequest()->query->all(),
        ));

        $config->setHeader([
            static::COL_ENTITY_NAME => static::HEADER_BUSINESS_ENTITY_NAME,
            static::COL_ENTITY_TYPE => static::HEADER_BUSINESS_ENTITY_TYPE,
            static::COL_ATTACHED_AT => static::HEADER_DATE_ATTACHED,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setSortable([
            static::COL_ENTITY_NAME,
            static::COL_ENTITY_TYPE,
            static::COL_ATTACHED_AT,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $countQuery = $this->prepareRowCountQuery($this->fileAttachmentViewDetailTableCriteriaTransfer);
        $countQuery->execute();
        $totalRows = $countQuery->fetchColumn();

        $query = $this->prepareRawQuery($config, $this->fileAttachmentViewDetailTableCriteriaTransfer);
        $query->execute();
        $queryResults = $query->fetchAll(PDO::FETCH_ASSOC);

        $this->setTotal((int)$totalRows);
        $this->setFiltered(count($queryResults));

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
            static::COL_ENTITY_NAME => $item[static::COL_ENTITY_NAME],
            static::COL_ENTITY_TYPE => $this->getEntityType($item),
            static::COL_ATTACHED_AT => $this->utilDateTimeService->formatDateTime($item[static::COL_ATTACHED_AT]),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    /**
     * @param array<mixed> $item
     *
     * @return string
     */
    protected function getEntityType(array $item): string
    {
        $translator = $this->getTranslator();
        if (!$translator) {
            return '';
        }

        return sprintf('%s', $translator->trans($item[static::COL_ENTITY_TYPE]));
    }

    /**
     * @param array<mixed> $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];
        $buttons[] = $this->generateFormButton(
            Url::generate(FileAbstractController::URL_PATH_UNLINK_FILE, [
                FileAbstractController::REQUEST_PARAM_ID_FILE => $item[static::COL_ID_FILE],
                FileAbstractController::REQUEST_PARAM_ENTITY_TYPE => $item[static::COL_ENTITY_TYPE],
                FileAbstractController::REQUEST_PARAM_ENTITY_ID => $item[static::COL_ENTITY_ID],
            ]),
            'Unlink',
            UnlinkFileForm::class,
            [
                'class' => 'btn btn-sm btn-outline safe-submit btn-remove',
                'icon' => 'fa fa-unlink',
                'data-qa' => 'unlink-button',
            ],
        );

        return implode(' ', $buttons);
    }

    protected function prepareCompanyFileQuery(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): SpyFileQuery {
        $companyFileQuery = $this->fileQuery::create()
            ->filterByIdFile($this->idFile)
            ->withColumn(SpyFileTableMap::COL_ID_FILE, static::COL_ID_FILE)
            ->useSpyCompanyFileQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(SpyCompanyFileTableMap::COL_CREATED_AT, static::COL_ATTACHED_AT)
                ->withColumn(SpyCompanyFileTableMap::COL_FK_COMPANY, static::COL_ENTITY_ID)
                    ->useCompanyQuery()
                        ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_ENTITY_NAME)
                        ->withColumn(static::COL_ENTITY_TYPE_COMPANY, static::COL_ENTITY_TYPE)
                        ->filterByName_Like($this->getSearchString())
                    ->endUse()
            ->endUse()
            ->select([
                static::COL_ID_FILE,
                static::COL_ATTACHED_AT,
                static::COL_ENTITY_ID,
                static::COL_ENTITY_NAME,
                static::COL_ENTITY_TYPE,
            ]);

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()) {
            // @phpstan-ignore-next-line
            $companyFileQuery
                ->useSpyCompanyFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()) {
            // @phpstan-ignore-next-line
            $companyFileQuery
                ->useSpyCompanyFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()), Criteria::LESS_THAN)
                ->endUse();
        }

        // @phpstan-ignore-next-line
        return $companyFileQuery;
    }

    protected function prepareCompanyBusinessUnitFileQuery(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): SpyFileQuery {
        $companyBusinessUnitFileQuery = $this->fileQuery::create()
            ->filterByIdFile($this->idFile)
            ->withColumn(SpyFileTableMap::COL_ID_FILE, static::COL_ID_FILE)
            ->useSpyCompanyBusinessUnitFileQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(SpyCompanyBusinessUnitFileTableMap::COL_CREATED_AT, static::COL_ATTACHED_AT)
                ->withColumn(SpyCompanyBusinessUnitFileTableMap::COL_FK_COMPANY_BUSINESS_UNIT, static::COL_ENTITY_ID)
                    ->useCompanyBusinessUnitQuery()
                        ->withColumn(SpyCompanyBusinessUnitTableMap::COL_NAME, static::COL_ENTITY_NAME)
                        ->withColumn(static::COL_ENTITY_TYPE_COMPANY_BUSINESS_UNIT, static::COL_ENTITY_TYPE)
                        ->filterByName_Like($this->getSearchString())
                    ->endUse()
            ->endUse()
            ->select([
                static::COL_ID_FILE,
                static::COL_ATTACHED_AT,
                static::COL_ENTITY_ID,
                static::COL_ENTITY_NAME,
                static::COL_ENTITY_TYPE,
            ]);

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()) {
            // @phpstan-ignore-next-line
            $companyBusinessUnitFileQuery
                ->useSpyCompanyBusinessUnitFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()) {
            // @phpstan-ignore-next-line
            $companyBusinessUnitFileQuery
                ->useSpyCompanyBusinessUnitFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()), Criteria::LESS_THAN)
                ->endUse();
        }

        // @phpstan-ignore-next-line
        return $companyBusinessUnitFileQuery;
    }

    protected function prepareCompanyUserFileQuery(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): SpyFileQuery {
        $companyUserFileQuery = $this->fileQuery::create()
            ->filterByIdFile($this->idFile)
            ->withColumn(SpyFileTableMap::COL_ID_FILE, static::COL_ID_FILE)
            ->useSpyCompanyUserFileQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(SpyCompanyUserFileTableMap::COL_CREATED_AT, static::COL_ATTACHED_AT)
                ->withColumn(SpyCompanyUserFileTableMap::COL_FK_COMPANY_USER, static::COL_ENTITY_ID)
                    ->useCompanyUserQuery()
                        ->useCustomerQuery()
                            ->withColumn(sprintf("CONCAT(%s, ' ', %s)", SpyCustomerTableMap::COL_FIRST_NAME, SpyCustomerTableMap::COL_LAST_NAME), static::COL_ENTITY_NAME)
                            ->withColumn(static::COL_ENTITY_TYPE_COMPANY_USER, static::COL_ENTITY_TYPE)
                            ->condition(
                                'firstAndLastNameCondition',
                                sprintf("CONCAT(%s, ' ', %s) LIKE ?", SpyCustomerTableMap::COL_FIRST_NAME, SpyCustomerTableMap::COL_LAST_NAME),
                                $this->getSearchString(),
                            )
                            ->combine(['firstAndLastNameCondition'])
                        ->endUse()
                    ->endUse()
            ->endUse()
            ->select([
                static::COL_ID_FILE,
                static::COL_ATTACHED_AT,
                static::COL_ENTITY_ID,
                static::COL_ENTITY_NAME,
                static::COL_ENTITY_TYPE,
            ]);

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()) {
            // @phpstan-ignore-next-line
            $companyUserFileQuery
                ->useSpyCompanyUserFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateFrom()), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()) {
            // @phpstan-ignore-next-line
            $companyUserFileQuery
                ->useSpyCompanyUserFileQuery()
                    ->filterByCreatedAt($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($fileAttachmentViewDetailTableCriteriaTransfer->getDateTo()), Criteria::LESS_THAN)
                ->endUse();
        }

        // @phpstan-ignore-next-line
        return $companyUserFileQuery;
    }

    protected function prepareAssetFileQuery(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): SpyFileQuery {
        $fileQuery = $this->fileQuery::create();

        $fileQuery
            ->useSpySspAssetFileQuery()
                ->filterByFkFile($this->idFile)
                ->withColumn(SpyFileTableMap::COL_ID_FILE, static::COL_ID_FILE)
                ->useSspAssetQuery()
                    ->withColumn(SpySspAssetFileTableMap::COL_CREATED_AT, static::COL_ATTACHED_AT)
                    ->withColumn(SpySspAssetTableMap::COL_ID_SSP_ASSET, static::COL_ENTITY_ID)
                    ->withColumn(SpySspAssetTableMap::COL_NAME, static::COL_ENTITY_NAME)
                    ->withColumn(static::COL_ENTITY_TYPE_ASSET, static::COL_ENTITY_TYPE)
                ->endUse()
            ->endUse()
            ->select([
                static::COL_ID_FILE,
                static::COL_ATTACHED_AT,
                static::COL_ENTITY_ID,
                static::COL_ENTITY_NAME,
                static::COL_ENTITY_TYPE,
            ]);

        return $fileQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
     *
     * @return array<int, array<int, mixed>|string>
     */
    protected function getFileUnionQuerySql(FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer): array
    {
        $companyFileParams = [];
        $companyBusinessUnitFileParams = [];
        $companyUserFileParams = [];
        $assetFileParams = [];
        $unionParts = [];

        if (!$fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() || $fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY) {
            $companyFileQuery = $this->prepareCompanyFileQuery($fileAttachmentViewDetailTableCriteriaTransfer);
            $unionParts[] = $companyFileQuery->createSelectSql($companyFileParams);
        }

        if (!$fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() || $fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT) {
            $companyBusinessUnitFileQuery = $this->prepareCompanyBusinessUnitFileQuery($fileAttachmentViewDetailTableCriteriaTransfer);
            $unionParts[] = $companyBusinessUnitFileQuery->createSelectSql($companyBusinessUnitFileParams);
        }

        if (!$fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() || $fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER) {
            $companyUserFileQuery = $this->prepareCompanyUserFileQuery($fileAttachmentViewDetailTableCriteriaTransfer);
            $unionParts[] = $companyUserFileQuery->createSelectSql($companyUserFileParams);
        }

        if (!$fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() || $fileAttachmentViewDetailTableCriteriaTransfer->getEntityType() === SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET) {
            $assetFileQuery = $this->prepareAssetFileQuery($fileAttachmentViewDetailTableCriteriaTransfer);
            $unionParts[] = $assetFileQuery->createSelectSql($assetFileParams);
        }

        $unionSql = implode(' UNION ALL ', array_map(fn ($sql) => "($sql)", $unionParts));

        return [
            $unionSql, [$companyFileParams, $companyBusinessUnitFileParams, $companyUserFileParams, $assetFileParams],
        ];
    }

    protected function prepareRawQuery(
        TableConfiguration $config,
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): StatementWrapper {
        $orderBy = $this->getOrderBy($config);

        /**
         * @var array<int, mixed> $params
         * @var string $unionSql
         */
        [$unionSql, $params] = $this->getFileUnionQuerySql($fileAttachmentViewDetailTableCriteriaTransfer);

        $sql = <<<SQL
            SELECT *
            FROM (
                $unionSql
            ) AS unioned
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
            SQL;

        $readConnection = $this->getReadConnection();

        /** @var \Propel\Runtime\Connection\StatementWrapper $preparedStatement */
        $preparedStatement = $readConnection->prepare($sql);

        $adapter = $this->getPropelAdapter();
        $dbMap = $this->getPropelDatabaseMap();

        foreach ($params as $param) {
            $adapter->bindValues($preparedStatement, $param, $dbMap);
        }

        $preparedStatement->bindValue(':limit', $this->getLimit(), PDO::PARAM_INT);
        $preparedStatement->bindValue(':offset', $this->getOffset(), PDO::PARAM_INT);

        return $preparedStatement;
    }

    protected function prepareRowCountQuery(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): StatementWrapper {
        /**
         * @var array<int, mixed> $params
         * @var string $unionSql
         */
        [$unionSql, $params] = $this->getFileUnionQuerySql($fileAttachmentViewDetailTableCriteriaTransfer);

        $sql = <<<SQL
            SELECT COUNT(*) AS total
            FROM (
                $unionSql
            ) AS unioned
            SQL;

        $connection = $this->getReadConnection();

        /** @var \Propel\Runtime\Connection\StatementWrapper $preparedStatement */
        $preparedStatement = $connection->prepare($sql);

        $adapter = $this->getPropelAdapter();
        $dbMap = $this->getPropelDatabaseMap();

        foreach ($params as $param) {
            $adapter->bindValues($preparedStatement, $param, $dbMap);
        }

        return $preparedStatement;
    }

    protected function getSearchString(): string
    {
        $searchTerm = !$this->getSearchTerm() ?: $this->getSearchTerm()['value'];

        return sprintf('%s%%', mb_strtolower($searchTerm));
    }

    protected function getOrderBy(TableConfiguration $config): string
    {
        $sortOrderCollection = $this->getOrders($config);
        $sortableColumns = $config->getSortable();

        if (!$sortOrderCollection || !$sortableColumns) {
            return '';
        }

        $orderClauses = [];
        foreach ($sortOrderCollection as $sortOrderItem) {
            $columnKey = $sortOrderItem[static::SORTABLE_COLUMN] ?? null;
            $direction = mb_strtoupper($sortOrderItem[static::SORTABLE_DIRECTION] ?? Criteria::ASC);

            if (!isset($sortableColumns[$columnKey])) {
                continue;
            }

            if (!in_array($direction, [Criteria::ASC, Criteria::DESC], true)) {
                $direction = Criteria::ASC;
            }

            $orderClauses[] = sprintf(
                'unioned.%s %s',
                $sortableColumns[$columnKey],
                $direction,
            );
        }

        return $orderClauses ? implode(', ', $orderClauses) : '';
    }

    protected function getPropelAdapter(): PdoAdapter
    {
        /** @var \Propel\Runtime\Adapter\Pdo\PdoAdapter $adapter */
        $adapter = Propel::getServiceContainer()->getAdapter(SpyFileTableMap::DATABASE_NAME);

        return $adapter;
    }

    protected function getPropelDatabaseMap(): DatabaseMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(SpyFileTableMap::DATABASE_NAME);
    }

    protected function getReadConnection(): ConnectionInterface
    {
        return Propel::getReadConnection(SpyFileTableMap::DATABASE_NAME);
    }
}
