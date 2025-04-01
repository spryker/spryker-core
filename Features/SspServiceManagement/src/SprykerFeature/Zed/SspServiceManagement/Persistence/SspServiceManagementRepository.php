<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemMetadataTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementPersistenceFactory getFactory()
 */
class SspServiceManagementRepository extends AbstractRepository implements SspServiceManagementRepositoryInterface
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
    protected const SORT_DIRECTION_ASC = 'ASC';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_DESC = 'DESC';

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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getFactory()->createSalesOrderItemQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        $serviceCollectionTransfer = new SspServiceCollectionTransfer();

        $query = $this->getFactory()->createSalesOrderItemQuery();

        $query->useSpySalesOrderItemProductAbstractTypeQuery()
            ->useSpySalesProductAbstractTypeQuery()
                ->filterByName($this->getFactory()->getConfig()->getProductServiveTypeName())
            ->endUse();

        $query = $this->joinOrderData($query);

        $query = $this->applyFilters($query, $sspServiceCriteriaTransfer);
        $query = $this->applyPagination($query, $sspServiceCriteriaTransfer, $serviceCollectionTransfer);
        $query = $this->applySorting($query, $sspServiceCriteriaTransfer);

        $serviceEntities = $query->groupByIdSalesOrderItem()->find();

        $sspServiceMapper = $this->getFactory()->createSspServiceMapper();
        $serviceTransfers = $sspServiceMapper->mapSalesOrderItemEntitiesToSspServiceTransfers($serviceEntities->getData());

        foreach ($serviceTransfers as $serviceTransfer) {
            $serviceCollectionTransfer->addService($serviceTransfer);
        }

        return $serviceCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function joinOrderData(SpySalesOrderItemQuery $query): SpySalesOrderItemQuery
    {
        $query
            ->useMetadataQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(SpySalesOrderItemMetadataTableMap::COL_SCHEDULED_AT, static::FIELD_SCHEDULED_AT)
            ->endUse()
            ->useOrderQuery()
                ->withColumn(SpySalesOrderTableMap::COL_ORDER_REFERENCE, static::FIELD_ORDER_REFERENCE)
                ->withColumn(SpySalesOrderTableMap::COL_ID_SALES_ORDER, 'id_sales_order')
            ->endUse();

        $query
            ->useStateQuery()
                ->withColumn('spy_oms_order_item_state.name', 'state_name')
            ->endUse()
            ->withColumn(SpySalesOrderItemTableMap::COL_NAME, 'product_name')
            ->withColumn(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, 'id_sales_order_item')
            ->withColumn(SpySalesOrderItemTableMap::COL_CREATED_AT, static::FIELD_CREATED_AT);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applyFilters(SpySalesOrderItemQuery $query, SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SpySalesOrderItemQuery
    {
        if (!$sspServiceCriteriaTransfer->getServiceConditions()) {
            return $query;
        }

        $serviceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditionsOrFail();

        if ($serviceConditionsTransfer->getProductType()) {
            $query->useSpySalesOrderItemProductAbstractTypeExistsQuery()
                ->useSpySalesProductAbstractTypeQuery()
                    ->filterByName($serviceConditionsTransfer->getProductType())
                ->endUse()
            ->endUse();
        }

        if ($serviceConditionsTransfer->getServicesSearchConditionGroup()) {
            $servicesSearchConditionGroup = $serviceConditionsTransfer->getServicesSearchConditionGroup();

            if ($servicesSearchConditionGroup->getProductName()) {
                $query->filterByName_Like('%' . $servicesSearchConditionGroup->getProductName() . '%');
            }

            if ($servicesSearchConditionGroup->getSku()) {
                $query->filterBySku_Like('%' . $servicesSearchConditionGroup->getSku() . '%');
            }

            if ($servicesSearchConditionGroup->getOrderReference()) {
                $query->useOrderQuery()
                    ->filterByOrderReference_Like('%' . $servicesSearchConditionGroup->getOrderReference() . '%')
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

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applyPagination(
        SpySalesOrderItemQuery $query,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        SspServiceCollectionTransfer $serviceCollectionTransfer
    ): SpySalesOrderItemQuery {
        if (!$sspServiceCriteriaTransfer->getPagination()) {
            return $query;
        }

        $paginationTransfer = $sspServiceCriteriaTransfer->getPaginationOrFail();
        $page = $paginationTransfer->getPage() ?? 1;
        $maxPerPage = $paginationTransfer->getMaxPerPage() ?? 10;
        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setPage($paginationModel->getPage());
        $paginationTransfer->setMaxPerPage($paginationModel->getMaxPerPage());
        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        $serviceCollectionTransfer->setPagination($paginationTransfer);

        /**
         * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
         */
        $query = $paginationModel->getQuery();

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applySorting(SpySalesOrderItemQuery $query, SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SpySalesOrderItemQuery
    {
        if (count($sspServiceCriteriaTransfer->getSortCollection()) === 0) {
            return $query->orderBy(static::FIELD_SCHEDULED_AT, Criteria::DESC);
        }

        foreach ($sspServiceCriteriaTransfer->getSortCollection() as $sortTransfer) {
            $direction = $sortTransfer->getDirection() === static::SORT_DIRECTION_ASC
                ? Criteria::ASC
                : Criteria::DESC;

            if (isset($this->getSortFieldMapping()[$sortTransfer->getField()])) {
                $query->orderBy($this->getSortFieldMapping()[$sortTransfer->getField()], $direction);
            }
        }

        return $query;
    }

    /**
     * @return array<string, string>
     */
    protected function getSortFieldMapping(): array
    {
        return [
            'order_reference' => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            'scheduled_at' => SpySalesOrderItemMetadataTableMap::COL_SCHEDULED_AT,
            'product_name' => SpySalesOrderItemTableMap::COL_NAME,
            'created_at' => SpySalesOrderItemTableMap::COL_CREATED_AT,
            'state' => 'spy_oms_order_item_state.name',
        ];
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractType>
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
}
