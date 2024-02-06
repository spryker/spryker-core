<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantUserCollectionTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserSearchConditionsTransfer;
use Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProvider;

class MerchantUserGuiTableMapper implements MerchantUserGuiTableMapperInterface
{
    /**
     * @uses \Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_MERCHANT_NAME = 'spy_merchant.name';

    /**
     * @uses \Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap::COL_STATUS
     *
     * @var string
     */
    protected const COL_MERCHANT_STATUS = 'spy_merchant.status';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_FIRST_NAME
     *
     * @var string
     */
    protected const COL_USER_FIRST_NAME = 'spy_user.first_name';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_LAST_NAME
     *
     * @var string
     */
    protected const COL_USER_LAST_NAME = 'spy_user.last_name';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_USERNAME
     *
     * @var string
     */
    protected const COL_USER_USERNAME = 'spy_user.username';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS
     *
     * @var string
     */
    protected const COL_USER_STATUS = 'spy_user.status';

    /**
     * @var string
     */
    protected const DIRECTION_ASC = 'ASC';

    /**
     * @var array<string, string>
     */
    protected const COLUMN_MAP = [
        MerchantUserGuiTableConfigurationProvider::COL_KEY_MERCHANT_NAME => self::COL_MERCHANT_NAME,
        MerchantUserGuiTableConfigurationProvider::COL_KEY_MERCHANT_STATUS => self::COL_MERCHANT_STATUS,
        MerchantUserGuiTableConfigurationProvider::COL_KEY_FIRST_NAME => self::COL_USER_FIRST_NAME,
        MerchantUserGuiTableConfigurationProvider::COL_KEY_LAST_NAME => self::COL_USER_LAST_NAME,
        MerchantUserGuiTableConfigurationProvider::COL_KEY_USERNAME => self::COL_USER_USERNAME,
        MerchantUserGuiTableConfigurationProvider::COL_KEY_STATUS => self::COL_USER_STATUS,
    ];

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer $merchantUserTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    public function mapMerchantUserTableCriteriaTransferToMerchantUserCriteriaTransfer(
        MerchantUserTableCriteriaTransfer $merchantUserTableCriteriaTransfer,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): MerchantUserCriteriaTransfer {
        $merchantUserCriteriaTransfer->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())
                ->setMerchantName($merchantUserTableCriteriaTransfer->getSearchTerm())
                ->setUserFirstName($merchantUserTableCriteriaTransfer->getSearchTerm())
                ->setUserLastName($merchantUserTableCriteriaTransfer->getSearchTerm())
                ->setUsername($merchantUserTableCriteriaTransfer->getSearchTerm()),
        )->setPagination(
            (new PaginationTransfer())
                ->setPage($merchantUserTableCriteriaTransfer->getPageOrFail())
                ->setMaxPerPage($merchantUserTableCriteriaTransfer->getPageSizeOrFail()),
        );

        if ($merchantUserTableCriteriaTransfer->getOrderBy() && isset(static::COLUMN_MAP[$merchantUserTableCriteriaTransfer->getOrderBy()])) {
            $merchantUserCriteriaTransfer->addSort(
                (new SortTransfer())
                    ->setField(static::COLUMN_MAP[$merchantUserTableCriteriaTransfer->getOrderBy()])
                    ->setIsAscending($merchantUserTableCriteriaTransfer->getOrderDirection() === static::DIRECTION_ASC),
            );
        }

        return $merchantUserCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCollectionTransfer $merchantUserCollectionTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function mapMerchantUserCollectionTransferToGuiTableDataResponseTransfer(
        MerchantUserCollectionTransfer $merchantUserCollectionTransfer,
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        $paginationTransfer = $merchantUserCollectionTransfer->getPaginationOrFail();
        $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPageOrFail())
            ->setPageSize($paginationTransfer->getMaxPerPageOrFail())
            ->setTotal($paginationTransfer->getNbResultsOrFail());

        return $guiTableDataResponseTransfer;
    }
}
