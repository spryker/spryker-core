<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSearchConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProvider;

class MerchantRelationshipGuiTableMapper implements MerchantRelationshipGuiTableMapperInterface
{
    /**
     * @uses \Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_RELATIONSHIP = 'spy_merchant_relationship.id_merchant_relationship';

    /**
     * @uses \Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap::COL_CREATED_AT
     *
     * @var string
     */
    protected const COL_MERCHANT_RELATIONSHIP_CREATED_AT = 'spy_merchant_relationship.created_at';

    /**
     * @uses \Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_COMPANY_NAME = 'spy_company.name';

    /**
     * @uses \Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_COMPANY_BUSINESS_UNIT_NAME = 'spy_company_business_unit.name';

    /**
     * @var string
     */
    protected const DIRECTION_ASC = 'ASC';

    /**
     * @var array<string, string>
     */
    protected const COLUMN_MAP = [
        MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_ID => self::COL_ID_MERCHANT_RELATIONSHIP,
        MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_CREATED_AT => self::COL_MERCHANT_RELATIONSHIP_CREATED_AT,
        MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_COMPANY => self::COL_COMPANY_NAME,
        MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNIT_OWNER => self::COL_COMPANY_BUSINESS_UNIT_NAME,
    ];

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    public function mapMerchantRelationshipTableCriteriaTransferToMerchantRelationshipCriteriaTransfer(
        MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $merchantRelationshipCriteriaTransfer->setMerchantRelationshipSearchConditions(
            (new MerchantRelationshipSearchConditionsTransfer())
                ->setAssigneeCompanyBusinessUnitName($merchantRelationshipTableCriteriaTransfer->getSearchTerm())
                ->setOwnerCompanyBusinessUnitName($merchantRelationshipTableCriteriaTransfer->getSearchTerm())
                ->setOwnerCompanyBusinessUnitCompanyName($merchantRelationshipTableCriteriaTransfer->getSearchTerm()),
        )->setPagination(
            (new PaginationTransfer())
                ->setPage($merchantRelationshipTableCriteriaTransfer->getPageOrFail())
                ->setMaxPerPage($merchantRelationshipTableCriteriaTransfer->getPageSizeOrFail()),
        );

        if (
            $merchantRelationshipTableCriteriaTransfer->getOrderBy()
            && isset(static::COLUMN_MAP[$merchantRelationshipTableCriteriaTransfer->getOrderBy()])
        ) {
            $sortCollectionTransfer = (new SortCollectionTransfer())->addSort(
                (new SortTransfer())
                    ->setField(static::COLUMN_MAP[$merchantRelationshipTableCriteriaTransfer->getOrderBy()])
                    ->setIsAscending($merchantRelationshipTableCriteriaTransfer->getOrderDirection() === static::DIRECTION_ASC),
            );

            $merchantRelationshipCriteriaTransfer->setSortCollection($sortCollectionTransfer);
        }

        $this->mapMerchantRelationshipTableCriteriaTransferToMerchantRelationshipConditionsTransfer(
            $merchantRelationshipTableCriteriaTransfer,
            $merchantRelationshipCriteriaTransfer->getMerchantRelationshipConditionsOrFail(),
        );

        return $merchantRelationshipCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function mapMerchantRelationshipCollectionTransferToGuiTableDataResponseTransfer(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer,
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        $paginationTransfer = $merchantRelationshipCollectionTransfer->getPaginationOrFail();
        $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPageOrFail())
            ->setPageSize($paginationTransfer->getMaxPerPageOrFail())
            ->setTotal($paginationTransfer->getNbResultsOrFail());

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer $merchantRelationshipConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer
     */
    protected function mapMerchantRelationshipTableCriteriaTransferToMerchantRelationshipConditionsTransfer(
        MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer,
        MerchantRelationshipConditionsTransfer $merchantRelationshipConditionsTransfer
    ): MerchantRelationshipConditionsTransfer {
        if ($merchantRelationshipTableCriteriaTransfer->getFilterCreatedAt()) {
            $merchantRelationshipConditionsTransfer->setRangeCreatedAt(
                $merchantRelationshipTableCriteriaTransfer->getFilterCreatedAt(),
            );
        }

        if ($merchantRelationshipTableCriteriaTransfer->getFilterInCompanyIds()) {
            $merchantRelationshipConditionsTransfer->setCompanyIds(
                $merchantRelationshipTableCriteriaTransfer->getFilterInCompanyIds(),
            );
        }

        return $merchantRelationshipConditionsTransfer;
    }
}
