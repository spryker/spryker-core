<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemMetadataTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyProductShipmentTypeTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspInquirySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspInquiryTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalPersistenceFactory getFactory()
 */
class SelfServicePortalRepository extends AbstractRepository implements SelfServicePortalRepositoryInterface
{
    /**
     * @var string
     */
    protected const FIELD_ORDER_REFERENCE = 'order_reference';

    /**
     * @var string
     */
    protected const FIELD_SCHEDULED_AT = 'scheduled_at';

    /**
     * @var string
     */
    protected const FIELD_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    protected const FIELD_ID_SALES_ORDER = 'id_sales_order';

    /**
     * @var string
     */
    protected const FIELD_STATE_NAME = 'state_name';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_NAME = 'product_name';

    /**
     * @var string
     */
    protected const FIELD_ID_SALES_ORDER_ITEM = 'id_sales_order_item';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_ASC = 'ASC';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_DESC = 'DESC';

    /**
     * @var array<string, string>
     */
    protected const SERVICE_SORT_FIELD_MAPPING = [
        'order_reference' => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        'scheduled_at' => SpySalesOrderItemMetadataTableMap::COL_SCHEDULED_AT,
        'product_name' => SpySalesOrderItemTableMap::COL_NAME,
        'created_at' => SpySalesOrderItemTableMap::COL_CREATED_AT,
        'state' => SpyOmsOrderItemStateTableMap::COL_NAME,
    ];

    /**
     * @param list<int> $productConcreteIds
     *
     * @return array<int, list<int>>
     */
    public function getShipmentTypeIdsGroupedByIdProductConcrete(array $productConcreteIds): array
    {
        $productShipmentTypeEntities = $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->find();

        $groupedShipmentTypeIds = [];
        foreach ($productShipmentTypeEntities as $productShipmentTypeEntity) {
            $groupedShipmentTypeIds[$productShipmentTypeEntity->getFkProduct()][] = $productShipmentTypeEntity->getFkShipmentType();
        }

        /** @var array<int, list<int>> $groupedShipmentTypeIds */
        return $groupedShipmentTypeIds;
    }

    /**
     * @param list<int> $productConcreteIds
     * @param string $shipmentTypeName
     *
     * @return array<int, list<int>>
     */
    public function getProductIdsWithShipmentType(array $productConcreteIds, string $shipmentTypeName): array
    {
        $productShipmentTypeEntities = $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->useSpyShipmentTypeQuery()
                ->filterByName($shipmentTypeName)
            ->endUse()
            ->filterByFkProduct_In($productConcreteIds)
            ->select(SpyProductShipmentTypeTableMap::COL_FK_PRODUCT)
            ->find();

        /** @var array<int, list<int>> $productConcreteIdsWithShipmentType */
        $productConcreteIdsWithShipmentType = $productShipmentTypeEntities->getData();

        return $productConcreteIdsWithShipmentType;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function getProductAbstractTypesByIdProductAbstract(int $idProductAbstract): array
    {
        $productAbstractTypeEntities = $this->getFactory()
            ->createProductAbstractTypeQuery()
            ->useProductAbstractToProductAbstractTypeQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductAbstractTypeMapper()
            ->mapProductAbstractTypeEntitiesToProductAbstractTypeTransfers($productAbstractTypeEntities);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer
     */
    public function getProductAbstractTypeCollection(): ProductAbstractTypeCollectionTransfer
    {
        $productAbstractTypeEntities = $this->getFactory()
            ->createProductAbstractTypeQuery()
            ->find();

        $productAbstractTypeCollectionTransfer = new ProductAbstractTypeCollectionTransfer();

        foreach ($productAbstractTypeEntities as $productAbstractTypeEntity) {
            $productAbstractTypeTransfer = (new ProductAbstractTypeTransfer())
                ->setIdProductAbstractType($productAbstractTypeEntity->getIdProductAbstractType())
                ->setKey($productAbstractTypeEntity->getKey())
                ->setName($productAbstractTypeEntity->getName());

            $productAbstractTypeCollectionTransfer->addProductAbstractType($productAbstractTypeTransfer);
        }

        return $productAbstractTypeCollectionTransfer;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function getProductAbstractTypesByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productAbstractTypeQuery = $this->getFactory()
            ->createProductAbstractTypeQuery()
            ->useProductAbstractToProductAbstractTypeQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->joinWithProductAbstractToProductAbstractType()
            ->withColumn('spy_product_abstract_to_product_abstract_type.fk_product_abstract', 'fk_product_abstract')
            ->groupBy(['spy_product_abstract_type.id_product_abstract_type', 'fk_product_abstract']);

        $productAbstractTypeEntities = $productAbstractTypeQuery->find();

        return $this->getFactory()
            ->createProductAbstractTypeMapper()
            ->mapProductAbstractTypeEntitiesWithVirtualColumnsToProductAbstractTypeTransfers($productAbstractTypeEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        $serviceCollectionTransfer = new SspServiceCollectionTransfer();

        $query = $this->getFactory()->getSalesOrderItemPropelQuery();

        $query = $this->joinServiceOrderData($query);

        $query->joinSalesOrderItemSspAsset(null, Criteria::LEFT_JOIN);

        $query = $this->applyServiceFilters($query, $sspServiceCriteriaTransfer);
        $query = $this->applyServiceSorting($query, $sspServiceCriteriaTransfer);

        $query = $query->groupByIdSalesOrderItem();

        $serviceEntities = $this->getServicePaginatedCollection($query, $sspServiceCriteriaTransfer);

        $sspServiceMapper = $this->getFactory()->createSspServiceMapper();
        $serviceTransfers = $sspServiceMapper->mapSalesOrderItemEntitiesToSspServiceTransfers($serviceEntities->getData());

        foreach ($serviceTransfers as $serviceTransfer) {
            $serviceCollectionTransfer->addService($serviceTransfer);
        }

        $serviceCollectionTransfer->setPagination($sspServiceCriteriaTransfer->getPagination());

        return $serviceCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function joinServiceOrderData(SpySalesOrderItemQuery $query): SpySalesOrderItemQuery
    {
        $query
            ->useMetadataQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(SpySalesOrderItemMetadataTableMap::COL_SCHEDULED_AT, static::FIELD_SCHEDULED_AT)
            ->endUse()
            ->useOrderQuery()
                ->withColumn(SpySalesOrderTableMap::COL_ORDER_REFERENCE, static::FIELD_ORDER_REFERENCE)
                ->withColumn(SpySalesOrderTableMap::COL_ID_SALES_ORDER, static::FIELD_ID_SALES_ORDER)
                ->withColumn(SpySalesOrderTableMap::COL_FIRST_NAME, 'first_name')
                ->withColumn(SpySalesOrderTableMap::COL_LAST_NAME, 'last_name')
                ->addJoin(
                    SpySalesOrderTableMap::COL_COMPANY_UUID,
                    'spy_company.uuid',
                    Criteria::LEFT_JOIN,
                )
                ->withColumn('spy_company.name', 'company_name')
            ->endUse();

        $query
            ->useStateQuery()
                ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, static::FIELD_STATE_NAME)
            ->endUse()
            ->withColumn(SpySalesOrderItemTableMap::COL_NAME, static::FIELD_PRODUCT_NAME)
            ->withColumn(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, static::FIELD_ID_SALES_ORDER_ITEM)
            ->withColumn(SpySalesOrderItemTableMap::COL_CREATED_AT, static::FIELD_CREATED_AT);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applyServiceFilters(SpySalesOrderItemQuery $query, SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SpySalesOrderItemQuery
    {
        if (!$sspServiceCriteriaTransfer->getServiceConditions()) {
            return $query;
        }

        $serviceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditionsOrFail();

        $productTypeNameToFilter = $serviceConditionsTransfer->getProductType() ?: $this->getFactory()->getConfig()->getServiceProductTypeName();

        if ($productTypeNameToFilter) {
            $query->useSpySalesOrderItemProductAbstractTypeQuery()
                ->useSpySalesProductAbstractTypeQuery()
                    ->filterByName($productTypeNameToFilter)
                ->endUse()
            ->endUse();
        }

        if ($serviceConditionsTransfer->getServicesSearchConditionGroup()) {
            $servicesSearchConditionGroup = $serviceConditionsTransfer->getServicesSearchConditionGroup();

            if ($servicesSearchConditionGroup->getProductName()) {
                $query->filterByName_Like(sprintf('%%%s%%', $servicesSearchConditionGroup->getProductName()));
            }

            if ($servicesSearchConditionGroup->getSku()) {
                $query->filterBySku_Like(sprintf('%%%s%%', $servicesSearchConditionGroup->getSku()));
            }

            if ($servicesSearchConditionGroup->getOrderReference()) {
                $query->useOrderQuery()
                        ->filterByOrderReference_Like(sprintf('%%%s%%', $servicesSearchConditionGroup->getOrderReference()))
                    ->endUse();
            }
        }

        if ($serviceConditionsTransfer->getCompanyBusinessUnitUuid()) {
            $query->useOrderQuery()
                ->filterByCompanyBusinessUnitUuid($serviceConditionsTransfer->getCompanyBusinessUnitUuid())
                ->endUse();
        }

        if ($serviceConditionsTransfer->getCompanyUuid()) {
            $query->useOrderQuery()
                ->filterByCompanyUuid($serviceConditionsTransfer->getCompanyUuid())
                ->endUse();
        }

        if ($serviceConditionsTransfer->getCustomerReference()) {
            $query->useOrderQuery()
                ->filterByCustomerReference($serviceConditionsTransfer->getCustomerReference())
                ->endUse();
        }

        if ($serviceConditionsTransfer->getSspAssetReferences() !== []) {
            $query->useSalesOrderItemSspAssetQuery()
                ->filterByReference_In($serviceConditionsTransfer->getSspAssetReferences())
                ->endUse();
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applyServiceSorting(SpySalesOrderItemQuery $query, SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SpySalesOrderItemQuery
    {
        if (count($sspServiceCriteriaTransfer->getSortCollection()) === 0) {
            return $query->orderBy(static::FIELD_SCHEDULED_AT, Criteria::DESC)
                ->orderBy(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, Criteria::ASC);
        }

        $serviceSortFieldMapping = static::SERVICE_SORT_FIELD_MAPPING;
        foreach ($sspServiceCriteriaTransfer->getSortCollection() as $sortTransfer) {
            $direction = $sortTransfer->getDirection() === static::SORT_DIRECTION_ASC
                ? Criteria::ASC
                : Criteria::DESC;

            if (isset($serviceSortFieldMapping[$sortTransfer->getField()])) {
                $query->orderBy($serviceSortFieldMapping[$sortTransfer->getField()], $direction);
            }
        }

        $query->orderBy(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, Criteria::ASC);

        return $query;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractType>
     */
    public function findProductAbstractTypesByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithProductAbstractType()
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<int, array<string>>
     */
    public function getProductTypesGroupedBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemProductAbstractTypeQuery = $this->getFactory()
            ->createSalesOrderItemProductAbstractTypeQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->joinWithSpySalesProductAbstractType();

        $salesOrderItemProductAbstractTypeEntities = $salesOrderItemProductAbstractTypeQuery->find();

        return $this->groupProductTypesBySalesOrderItemId($salesOrderItemProductAbstractTypeEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductAbstractType> $salesOrderItemProductAbstractTypeEntities
     *
     * @return array<int, array<string>>
     */
    protected function groupProductTypesBySalesOrderItemId(Collection $salesOrderItemProductAbstractTypeEntities): array
    {
        $productTypesBySalesOrderItemId = [];

        foreach ($salesOrderItemProductAbstractTypeEntities as $salesOrderItemProductAbstractTypeEntity) {
            /**
             * @var \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductAbstractType $salesOrderItemProductAbstractTypeEntity
             */
            $idSalesOrderItem = $salesOrderItemProductAbstractTypeEntity->getFkSalesOrderItem();
            $productTypeName = $salesOrderItemProductAbstractTypeEntity->getSpySalesProductAbstractType()->getName();

            if (!isset($productTypesBySalesOrderItemId[$idSalesOrderItem])) {
                $productTypesBySalesOrderItemId[$idSalesOrderItem] = [];
            }

            if (!in_array($productTypeName, $productTypesBySalesOrderItemId[$idSalesOrderItem])) {
                $productTypesBySalesOrderItemId[$idSalesOrderItem][] = $productTypeName;
            }
        }

        return $productTypesBySalesOrderItemId;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCollectionTransfer
    {
        $fileAttachmentCollectionTransfer = new FileAttachmentCollectionTransfer();

        $companyFiles = $this->getCompanyFiles($fileAttachmentCriteriaTransfer);
        $companyUserFiles = $this->getCompanyUserFiles($fileAttachmentCriteriaTransfer);
        $companyBusinessUnitFiles = $this->getCompanyBusinessUnitFiles($fileAttachmentCriteriaTransfer);
        $sspAssetFiles = $this->getSspAssetFiles($fileAttachmentCriteriaTransfer);

        $fileAttachmentTransfers = array_merge(
            $this->getFactory()->createCompanyFileMapper()->mapCompanyFileEntitiesToFileAttachmentTransfers($companyFiles),
            $this->getFactory()->createCompanyUserFileMapper()->mapCompanyUserFileEntitiesToFileAttachmentTransfers($companyUserFiles),
            $this->getFactory()->createCompanyBusinessUnitFileMapper()->mapCompanyBusinessUnitFileEntitiesToFileAttachmentTransfers($companyBusinessUnitFiles),
            $this->getFactory()->createSspAssetFileMapper()->mapSspAssetFileEntitiesToFileAttachmentTransfers($sspAssetFiles),
        );

        return $fileAttachmentCollectionTransfer->setFileAttachments(new ArrayObject($fileAttachmentTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFile>
     */
    protected function getCompanyFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFile> $companyFilesCollection */
        $companyFilesCollection = $query->find();

        return $companyFilesCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile>
     */
    protected function getCompanyUserFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyUserFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile> $companyUserFilesCollection */
        $companyUserFilesCollection = $query->find();

        return $companyUserFilesCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile>
     */
    protected function getCompanyBusinessUnitFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile> $companyBusinessUnitFilesCollection */
        $companyBusinessUnitFilesCollection = $query->find();

        return $companyBusinessUnitFilesCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile>
     */
    protected function getSspAssetFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createSspAssetFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile> $assetFilesCollection */
        $assetFilesCollection = $query->find();

        return $assetFilesCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer,
        array $queryStrategies
    ): FileAttachmentFileCollectionTransfer {
        $query = $this->getFactory()
            ->getFilePropelQuery()
            ->leftJoinSpyFileInfo()
            ->groupBy(SpyFileTableMap::COL_ID_FILE);

        $query = $this->applyFileAttachmentPermissionStrategies($query, $fileAttachmentFileCriteriaTransfer, $queryStrategies);
        $query = $this->applyFileAttachmentSearch($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyFileAttachmentTypeFilter($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyFileAttachmentDateRangeFilter($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyFileAttachmentUuidFilter($query, $fileAttachmentFileCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $fileAttachmentFileCriteriaTransfer->getSortCollection();
        $query = $this->applyFileAttachmentSorting($query, $sortTransfers);

        $fileAttachmentFileCollectionTransfer = new FileAttachmentFileCollectionTransfer();
        $paginationTransfer = $fileAttachmentFileCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $query = $this->applyPagination($query, $paginationTransfer);
            $fileAttachmentFileCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createFileMapper()
            ->mapEntityCollectionToTransferCollection(
                $query->find(),
                $fileAttachmentFileCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileAttachmentPermissionStrategies(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer,
        array $queryStrategies
    ): SpyFileQuery {
        foreach ($queryStrategies as $strategy) {
            $query = $strategy->apply($query, $fileAttachmentFileCriteriaTransfer);
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileAttachmentSearch(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $searchString = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileSearchConditionsOrFail()->getSearchString();
        if ($searchString) {
            $query->filterByFileName_Like(sprintf('%%%s%%', $searchString))
                ->_or()
                ->filterByFileReference_Like(sprintf('%%%s%%', $searchString));
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileAttachmentTypeFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $fileTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getFileTypes();

        if (!$fileTypes) {
            return $query;
        }

        return $query
            ->useSpyFileInfoQuery()
                ->filterByExtension_In($fileTypes)
            ->endUse();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileAttachmentDateRangeFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $rangeCreatedAt = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getRangeCreatedAt();
        if (!$rangeCreatedAt) {
            return $query;
        }

        if ($rangeCreatedAt->getFrom()) {
            $query->useSpyFileInfoQuery()
                    ->filterByCreatedAt($rangeCreatedAt->getFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($rangeCreatedAt->getTo()) {
            $query->useSpyFileInfoQuery()
                    ->filterByCreatedAt($rangeCreatedAt->getTo(), Criteria::LESS_EQUAL)
                ->endUse();
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileAttachmentUuidFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $uuids = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getUuids();
        if ($uuids !== []) {
            $query
                ->filterByUuid_In($uuids);
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getLimit() === null || $paginationTransfer->getOffset() === null) {
            $paginationTransfer = $this->getPaginationTransfer($query, $paginationTransfer);
        }

        $query
            ->setLimit($paginationTransfer->getLimitOrFail())
            ->setOffset($paginationTransfer->getOffsetOrFail());

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(
        ModelCriteria $query,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        $page = $paginationTransfer->getPage() ?? 1;
        $maxPerPage = $paginationTransfer->getMaxPerPage() ?? 10;
        $paginationModel = $query->paginate($page, $maxPerPage);
        $nbResults = $paginationModel->getNbResults();

        return $paginationTransfer
            ->setNbResults($nbResults)
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage())
            ->setOffset(($page - 1) * $maxPerPage)
            ->setLimit($maxPerPage);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyFileAttachmentSorting(ModelCriteria $query, ArrayObject $sortTransfers): ModelCriteria
    {
        $fileAttachmentSortFieldMapping = $this->getFileAttachmentSortFieldMapping();
        foreach ($sortTransfers as $sortTransfer) {
            $query
                ->groupBy($fileAttachmentSortFieldMapping[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail())
                ->orderBy(
                    $fileAttachmentSortFieldMapping[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail(),
                    $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
                );
        }

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCollectionTransfer {
         $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

         $sspInquiryQuery = $this->getFactory()->createSspInquiryQuery()
            ->joinWithStateMachineItemState();

         $sspInquiryQuery = $this->applyInquiryFilters($sspInquiryQuery, $sspInquiryCriteriaTransfer);
         $sspInquiryEntities = $this->getPaginatedInquiryCollection($sspInquiryQuery, $sspInquiryCriteriaTransfer->getPagination());
         $sspInquiryMapper = $this->getFactory()->createSspInquiryMapper();

        foreach ($sspInquiryEntities as $sspInquiryEntity) {
             $sspInquiryCollectionTransfer->addSspInquiry(
                 $sspInquiryMapper->mapSspInquiryEntityToSspInquiryTransfer($sspInquiryEntity, new SspInquiryTransfer()),
             );
        }

         $sspInquiryCollectionTransfer->setPagination($sspInquiryCriteriaTransfer->getPagination());

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryFileCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryFileQuery = $this->getFactory()->createSspInquiryFileQuery();

        $sspInquiryFileQuery->filterByFkSspInquiry_In($sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryIds());

        $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile> $sspInquiryFileEntities
         */
        $sspInquiryFileEntities = $sspInquiryFileQuery->find();

        if ($sspInquiryFileEntities->count() === 0) {
            return $sspInquiryCollectionTransfer;
        }

        return $this->getFactory()
            ->createSspInquiryMapper()
            ->mapSspInquiryFileEntitiesToSspInquiryCollectionTransfer(
                $sspInquiryFileEntities,
                $sspInquiryCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryOrderCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryOrderQuery = $this->getFactory()->createSspInquiryOrderQuery();

         $sspInquiryOrderQuery->filterByFkSspInquiry_In($sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryIds());

         $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

        foreach ($sspInquiryOrderQuery->find() as $sspInquiryOrderEntity) {
             $sspInquiryCollectionTransfer->addSspInquiry(
                 (new SspInquiryTransfer())
                    ->setIdSspInquiry($sspInquiryOrderEntity->getFkSspInquiry())
                    ->setOrder((new OrderTransfer())->setIdSalesOrder($sspInquiryOrderEntity->getFkSalesOrder())),
             );
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquirySspAssetCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        $sspInquirySspAssetQuery = $this->getFactory()->createSspInquirySspAssetQuery();

        $sspInquirySspAssetQuery->filterByFkSspInquiry_In($sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryIds());

        $inquiryCollectionTransfer = new SspInquiryCollectionTransfer();

        foreach ($sspInquirySspAssetQuery->find() as $sspInquirySspAssetEntity) {
            $inquiryCollectionTransfer->addSspInquiry(
                (new SspInquiryTransfer())
                    ->setIdSspInquiry($sspInquirySspAssetEntity->getFkSspInquiry())
                    ->setSspAsset((new SspAssetTransfer())->setIdSspAsset($sspInquirySspAssetEntity->getFkSspAsset())),
            );
        }

        return $inquiryCollectionTransfer;
    }

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array
    {
        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $sspInquiryQuery */
         $sspInquiryQuery = $this->getFactory()
            ->createSspInquiryQuery()
            ->joinWithStateMachineItemState()
            ->useStateMachineItemStateQuery()
            ->joinWithProcess()
            ->endUse();

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $sspInquiryEntities */
         $sspInquiryEntities = $sspInquiryQuery
            ->filterByFkStateMachineItemState_In($stateIds)
            ->find();

        return $this->getFactory()->createSspInquiryMapper()->mapSspInquiryEntityCollectionToStateMachineItemTransfers(
            $sspInquiryEntities,
        );
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery
     */
    protected function applyInquiryFilters(SpySspInquiryQuery $sspInquiryQuery, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SpySspInquiryQuery
    {
         $sspInquiryQuery = $this->applySspInquirySorting($sspInquiryQuery, $sspInquiryCriteriaTransfer->getSortCollection());

         $sspInquiryConditions = $sspInquiryCriteriaTransfer->getSspInquiryConditions();

        if (!$sspInquiryConditions) {
            return $sspInquiryQuery;
        }

        if ($sspInquiryConditions->getSspInquiryIds() !== []) {
             $sspInquiryQuery->filterByIdSspInquiry_In($sspInquiryConditions->getSspInquiryIds());
        }

        if ($sspInquiryConditions->getReferences() !== []) {
             $sspInquiryQuery->filterByReference_In($sspInquiryConditions->getReferences());
        }

        if ($sspInquiryConditions->getType() !== null) {
             $sspInquiryQuery->filterByType($sspInquiryConditions->getType());
        }

        if ($sspInquiryConditions->getStatus() !== null) {
             $sspInquiryQuery
                ->useStateMachineItemStateQuery()
                    ->filterByName($sspInquiryConditions->getStatus())
                ->endUse();
        }

        $this->applySspInquiryOwnerFilter($sspInquiryQuery, $sspInquiryConditions);

        if ($sspInquiryConditions->getCreatedDateFrom() !== null) {
             $sspInquiryQuery->filterByCreatedAt($sspInquiryConditions->getCreatedDateFrom(), ModelCriteria::GREATER_EQUAL);
        }

        if ($sspInquiryConditions->getCreatedDateTo() !== null) {
             $sspInquiryQuery->filterByCreatedAt($sspInquiryConditions->getCreatedDateTo(), ModelCriteria::LESS_EQUAL);
        }

        if ($sspInquiryConditions->getIdStore() !== null) {
             $sspInquiryQuery->filterByFkStore($sspInquiryConditions->getIdStore());
        }

        if ($sspInquiryConditions->getStoreName() !== null) {
            $sspInquiryQuery
                ->useSpyStoreQuery()
                    ->filterByName($sspInquiryConditions->getStoreName())
                ->endUse();
        }

        if ($sspInquiryConditions->getSspAssetIds() !== []) {
             $sspInquiryQuery
                 ->joinSpySspInquirySspAsset()
                 ->withColumn(SpySspInquirySspAssetTableMap::COL_FK_SSP_ASSET, SspAssetTransfer::ID_SSP_ASSET)
                 ->useSpySspInquirySspAssetExistsQuery()
                     ->filterByFkSspAsset_In($sspInquiryConditions->getSspAssetIds())
                 ->endUse();
        }

        if ($sspInquiryConditions->getSspAssetReferences() !== []) {
            $sspInquiryQuery
                ->useSpySspInquirySspAssetExistsQuery()
                    ->useSpySspAssetQuery()
                        ->filterByReference_In($sspInquiryConditions->getSspAssetReferences())
                    ->endUse()
                ->endUse();
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditions
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery
     */
    public function applySspInquiryOwnerFilter(SpySspInquiryQuery $sspInquiryQuery, SspInquiryConditionsTransfer $sspInquiryConditions): SpySspInquiryQuery
    {
         $sspInquiryOwnerConditionGroup = $sspInquiryConditions->getSspInquiryOwnerConditionGroup();

        if ($sspInquiryOwnerConditionGroup) {
            $hasOwnerCondition = false;
            $companyUserQuery = $sspInquiryQuery->useSpyCompanyUserQuery();

            if ($sspInquiryOwnerConditionGroup->getCompanyUser()?->getIdCompanyUser()) {
                $hasOwnerCondition = true;
                $companyUserQuery->filterByIdCompanyUser($sspInquiryOwnerConditionGroup->getCompanyUser()->getIdCompanyUser());
            }

            if ($sspInquiryOwnerConditionGroup->getIdCompany()) {
                if ($hasOwnerCondition) {
                    $companyUserQuery->_or();
                }

                $hasOwnerCondition = true;
                $companyUserQuery->filterByFkCompany($sspInquiryOwnerConditionGroup->getIdCompany());
            }

            if ($sspInquiryOwnerConditionGroup->getIdCompanyBusinessUnit()) {
                if ($hasOwnerCondition) {
                    $companyUserQuery->_or();
                }

                $companyUserQuery->filterByFkCompanyBusinessUnit($sspInquiryOwnerConditionGroup->getIdCompanyBusinessUnitOrFail());
            }

            $companyUserQuery->endUse();
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SortTransfer> $sortCollection
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery
     */
    protected function applySspInquirySorting(SpySspInquiryQuery $sspInquiryQuery, ArrayObject $sortCollection): SpySspInquiryQuery
    {
        foreach ($sortCollection as $sort) {
            $field = $sort->getFieldOrFail();
            if ($field === SspInquiryTransfer::CREATED_DATE) {
                $field = SpySspInquiryTableMap::COL_CREATED_AT;
            }
            $sspInquiryQuery->orderBy($field, $sort->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getPaginatedInquiryCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null): Collection
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPageOrFail();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPageOrFail();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getResults();
    }

    /**
     * @return array<string, string>
     */
    protected function getFileAttachmentSortFieldMapping(): array
    {
        return [
            'fileType' => SpyFileInfoTableMap::COL_EXTENSION,
            'size' => SpyFileInfoTableMap::COL_SIZE,
            'createdAt' => SpyFileInfoTableMap::COL_CREATED_AT,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer
    {
        $sspAssetCollectionTransfer = new SspAssetCollectionTransfer();

        $sspAssetQuery = $this->getFactory()->createSspAssetQuery();

        $sspAssetQuery = $this->applyAssetConditions($sspAssetQuery, $sspAssetCriteriaTransfer);
        $sspAssetQuery = $this->applyAssetSorting($sspAssetQuery, $sspAssetCriteriaTransfer);

        if ($sspAssetCriteriaTransfer->getInclude()?->getWithOwnerCompanyBusinessUnit()) {
            $sspAssetQuery->joinWithSpyCompanyBusinessUnit(Criteria::LEFT_JOIN);
        }

        $sspAssetEntities = $this->getAssetPaginatedCollection($sspAssetQuery, $sspAssetCriteriaTransfer->getPagination());
        $sspAssetIds = [];
        foreach ($sspAssetEntities as $sspAssetEntity) {
            $sspAssetTransfer = $this->getFactory()
                ->createAssetMapper()
                ->mapSpySspAssetEntityToSspAssetTransfer($sspAssetEntity, new SspAssetTransfer());

            if ($sspAssetCriteriaTransfer->getInclude()) {
                $sspAssetTransfer = $this->getFactory()
                    ->createAssetMapper()
                    ->mapSpySspAssetEntityToSspAssetTransferIncludes(
                        $sspAssetEntity,
                        $sspAssetTransfer,
                        $sspAssetCriteriaTransfer->getIncludeOrFail(),
                    );
            }

            $sspAssetCollectionTransfer->addSspAsset($sspAssetTransfer);
            $sspAssetIds[] = $sspAssetTransfer->getIdSspAsset();
        }

        $sspAssetCollectionTransfer->setPagination($sspAssetCriteriaTransfer->getPagination());

        if (!$sspAssetCriteriaTransfer->getInclude()?->getWithAssignedBusinessUnits()) {
            return $sspAssetCollectionTransfer;
        }

        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery $sspAssetToCompanyBusinessUnitQuery */
        $sspAssetToCompanyBusinessUnitQuery = $this->getFactory()->createSspAssetToCompanyBusinessUnitQuery()
            ->filterByFkSspAsset_In($sspAssetIds)
            ->joinWithSpyCompanyBusinessUnit()
            ->useSpyCompanyBusinessUnitQuery()
                ->joinWithCompany()
            ->endUse();

        if ($sspAssetCriteriaTransfer->getSspAssetConditions()) {
            if ($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitId()) {
                $sspAssetToCompanyBusinessUnitQuery->filterByFkCompanyBusinessUnit(
                    $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitId(),
                );
            }

            if ($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitCompanyId()) {
                $sspAssetToCompanyBusinessUnitQuery
                    ->useSpyCompanyBusinessUnitQuery()
                        ->filterByFkCompany($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitCompanyId())
                    ->endUse();
            }
        }

        $sspAssetToCompanyBusinessUnitEntities = $sspAssetToCompanyBusinessUnitQuery->find();

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            foreach ($sspAssetToCompanyBusinessUnitEntities as $sspAssetToCompanyBusinessUnit) {
                if ($sspAssetToCompanyBusinessUnit->getFkSspAsset() === $sspAssetTransfer->getIdSspAsset()) {
                    $sspAssetTransfer->addBusinessUnitAssignment(
                        (new SspAssetBusinessUnitAssignmentTransfer())
                            ->setCompanyBusinessUnit(
                                (new CompanyBusinessUnitTransfer())
                                    ->setIdCompanyBusinessUnit($sspAssetToCompanyBusinessUnit->getFkCompanyBusinessUnit())
                                    ->setName($sspAssetToCompanyBusinessUnit->getSpyCompanyBusinessUnit()->getName())
                                    ->setCompany(
                                        (new CompanyTransfer())
                                            ->setIdCompany($sspAssetToCompanyBusinessUnit->getSpyCompanyBusinessUnit()->getFkCompany())
                                            ->setName($sspAssetToCompanyBusinessUnit->getSpyCompanyBusinessUnit()->getCompany()->getName()),
                                    ),
                            )
                            ->setAssignedAt($sspAssetToCompanyBusinessUnit->getCreatedAt()->format('Y-m-d H:i:s')),
                    );
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery $sspAssetQuery
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery
     */
    protected function applyAssetConditions(
        SpySspAssetQuery $sspAssetQuery,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SpySspAssetQuery {
        $sspAssetConditionsTransfer = $sspAssetCriteriaTransfer->getSspAssetConditions();

        if (!$sspAssetConditionsTransfer) {
            return $sspAssetQuery;
        }

        if ($sspAssetConditionsTransfer->getSspAssetIds()) {
            $sspAssetQuery->filterByIdSspAsset_In($sspAssetConditionsTransfer->getSspAssetIds());
        }

        if ($sspAssetConditionsTransfer->getReferences()) {
            $sspAssetQuery->filterByReference_In($sspAssetConditionsTransfer->getReferences());
        }

        if ($sspAssetConditionsTransfer->getStatus()) {
            $sspAssetQuery->filterByStatus($sspAssetConditionsTransfer->getStatus());
        }

        if ($sspAssetConditionsTransfer->getAssignedBusinessUnitId()) {
            $sspAssetQuery
                ->useSpySspAssetToCompanyBusinessUnitExistsQuery()
                    ->filterByFkCompanyBusinessUnit($sspAssetConditionsTransfer->getAssignedBusinessUnitId())
                ->endUse();
        }

        if ($sspAssetConditionsTransfer->getAssignedBusinessUnitCompanyId()) {
            $sspAssetQuery
                ->useSpySspAssetToCompanyBusinessUnitExistsQuery()
                    ->useSpyCompanyBusinessUnitQuery()
                        ->filterByFkCompany($sspAssetConditionsTransfer->getAssignedBusinessUnitCompanyId())
                    ->endUse()
                ->endUse();
        }

        if ($sspAssetConditionsTransfer->getStatuses() !== []) {
            $sspAssetQuery->filterByStatus_In($sspAssetConditionsTransfer->getStatuses());
        }

        if ($sspAssetConditionsTransfer->getSearchText()) {
            $searchText = '%' . $sspAssetConditionsTransfer->getSearchTextOrFail() . '%';
            $sspAssetQuery->filterByName_Like($searchText)
                ->_or()
                ->filterByReference_Like($searchText)
                ->_or()
                ->filterBySerialNumber_Like($searchText);
        }

        if ($sspAssetConditionsTransfer->getImageFileIds() !== []) {
            $sspAssetQuery->filterByFkImageFile_In($sspAssetConditionsTransfer->getImageFileIds());
        }

        return $sspAssetQuery;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery $sspAssetQuery
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery
     */
    protected function applyAssetSorting(
        SpySspAssetQuery $sspAssetQuery,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SpySspAssetQuery {
        $sortCollection = $sspAssetCriteriaTransfer->getSortCollection();

        if (!$sortCollection->count()) {
            return $sspAssetQuery;
        }

        foreach ($sortCollection as $sort) {
            $field = $sort->getFieldOrFail();
            if ($field === SspAssetTransfer::CREATED_DATE) {
                $field = SpySspAssetTableMap::COL_CREATED_AT;
            }
            $direction = $sort->getIsAscending() ? Criteria::ASC : Criteria::DESC;
            $sspAssetQuery->orderBy($field, $direction);
        }

        return $sspAssetQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getAssetPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null): Collection
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPageOrFail();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPageOrFail();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getResults();
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<int, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function getSspAssetsIndexedByIdSalesOrderItem(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemSspAssetQuery = $this->getFactory()
            ->getSalesOrderItemSspAssetQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds);

        $salesOrderItemSspAssetEntities = $salesOrderItemSspAssetQuery->find();
        $sspAssetsIndexedByIdSalesOrderItem = [];

        foreach ($salesOrderItemSspAssetEntities as $salesOrderItemSspAssetEntity) {
            $sspAssetTransfer = new SspAssetTransfer();
            $sspAssetTransfer->fromArray($salesOrderItemSspAssetEntity->toArray(), true);

            $sspAssetsIndexedByIdSalesOrderItem[(int)$salesOrderItemSspAssetEntity->getFkSalesOrderItem()] = $sspAssetTransfer;
        }

        return $sspAssetsIndexedByIdSalesOrderItem;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function getServicePaginatedCollection(
        SpySalesOrderItemQuery $query,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): Collection {
        if (!$sspServiceCriteriaTransfer->getPagination()) {
            return $query->find();
        }

        $paginationTransfer = $sspServiceCriteriaTransfer->getPaginationOrFail();
        $page = $paginationTransfer
            ->requirePage()
            ->getPageOrFail();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPageOrFail();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getResults();
    }
}
