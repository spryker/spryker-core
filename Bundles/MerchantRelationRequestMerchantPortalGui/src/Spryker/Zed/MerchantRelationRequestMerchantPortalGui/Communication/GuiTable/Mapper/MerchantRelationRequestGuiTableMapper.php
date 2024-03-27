<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\Mapper;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestSearchConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationRequestGuiTableConfigurationProvider;

class MerchantRelationRequestGuiTableMapper implements MerchantRelationRequestGuiTableMapperInterface
{
    /**
     * @uses \Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_RELATION_REQUEST = 'spy_merchant_relation_request.id_merchant_relation_request';

    /**
     * @uses \Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap::COL_CREATED_AT
     *
     * @var string
     */
    protected const COL_MERCHANT_RELATION_REQUEST_CREATED_AT = 'spy_merchant_relation_request.created_at';

    /**
     * @uses \Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap::COL_STATUS
     *
     * @var string
     */
    protected const COL_MERCHANT_RELATION_REQUEST_STATUS = 'spy_merchant_relation_request.status';

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
        MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_ID => self::COL_ID_MERCHANT_RELATION_REQUEST,
        MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_CREATED_AT => self::COL_MERCHANT_RELATION_REQUEST_CREATED_AT,
        MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_STATUS => self::COL_MERCHANT_RELATION_REQUEST_STATUS,
        MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_COMPANY => self::COL_COMPANY_NAME,
        MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNIT_OWNER => self::COL_COMPANY_BUSINESS_UNIT_NAME,
    ];

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    public function mapMerchantRelationRequestTableCriteriaTransferToMerchantRelationRequestCriteriaTransfer(
        MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer {
        $merchantRelationRequestCriteriaTransfer->setMerchantRelationRequestSearchConditions(
            (new MerchantRelationRequestSearchConditionsTransfer())
                ->setAssigneeCompanyBusinessUnitName($merchantRelationRequestTableCriteriaTransfer->getSearchTerm())
                ->setOwnerCompanyBusinessUnitName($merchantRelationRequestTableCriteriaTransfer->getSearchTerm())
                ->setOwnerCompanyBusinessUnitCompanyName($merchantRelationRequestTableCriteriaTransfer->getSearchTerm()),
        )->setPagination(
            (new PaginationTransfer())
                ->setPage($merchantRelationRequestTableCriteriaTransfer->getPageOrFail())
                ->setMaxPerPage($merchantRelationRequestTableCriteriaTransfer->getPageSizeOrFail()),
        );

        if (
            $merchantRelationRequestTableCriteriaTransfer->getOrderBy()
            && isset(static::COLUMN_MAP[$merchantRelationRequestTableCriteriaTransfer->getOrderBy()])
        ) {
            $merchantRelationRequestCriteriaTransfer->addSort(
                (new SortTransfer())
                    ->setField(static::COLUMN_MAP[$merchantRelationRequestTableCriteriaTransfer->getOrderBy()])
                    ->setIsAscending($merchantRelationRequestTableCriteriaTransfer->getOrderDirection() === static::DIRECTION_ASC),
            );
        }

        $this->mapMerchantRelationRequestTableCriteriaTransferToMerchantRelationRequestConditionsTransfer(
            $merchantRelationRequestTableCriteriaTransfer,
            $merchantRelationRequestCriteriaTransfer->getMerchantRelationRequestConditionsOrFail(),
        );

        return $merchantRelationRequestCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    public function mapMerchantUserTransferToMerchantRelationRequestCriteriaTransfer(
        MerchantUserTransfer $merchantUserTransfer,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer {
        $merchantRelationRequestCriteriaTransfer->setMerchantRelationRequestConditions(
            (new MerchantRelationRequestConditionsTransfer())->addIdMerchant($merchantUserTransfer->getIdMerchantOrFail()),
        );

        return $merchantRelationRequestCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function mapMerchantRelationRequestCollectionTransferToGuiTableDataResponseTransfer(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer,
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        $paginationTransfer = $merchantRelationRequestCollectionTransfer->getPaginationOrFail();
        $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPageOrFail())
            ->setPageSize($paginationTransfer->getMaxPerPageOrFail())
            ->setTotal($paginationTransfer->getNbResultsOrFail());

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer
     */
    protected function mapMerchantRelationRequestTableCriteriaTransferToMerchantRelationRequestConditionsTransfer(
        MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer,
        MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
    ): MerchantRelationRequestConditionsTransfer {
        if ($merchantRelationRequestTableCriteriaTransfer->getFilterCreatedAt()) {
            $merchantRelationRequestConditionsTransfer->setRangeCreatedAt(
                $merchantRelationRequestTableCriteriaTransfer->getFilterCreatedAt(),
            );
        }

        if ($merchantRelationRequestTableCriteriaTransfer->getFilterInStatuses()) {
            $merchantRelationRequestConditionsTransfer->setStatuses(
                $merchantRelationRequestTableCriteriaTransfer->getFilterInStatuses(),
            );
        }

        if ($merchantRelationRequestTableCriteriaTransfer->getFilterInCompanyIds()) {
            $merchantRelationRequestConditionsTransfer->setCompanyIds(
                $merchantRelationRequestTableCriteriaTransfer->getFilterInCompanyIds(),
            );
        }

        return $merchantRelationRequestConditionsTransfer;
    }
}
