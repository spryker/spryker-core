<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\DashboardDataProvider;

use Generated\Shared\Transfer\DashboardComponentInquiryTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;
use SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class InquiryDashboardDataProvider implements InquiryDashboardDataProviderInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Orm\Zed\SspInquiryManagement\Persistence\Map\SpySspInquiryTableMap::COL_CREATED_AT
     *
     * @var string
     */
    protected const FIELD_SSP_INQUIRY_CREATED_AT = 'spy_ssp_inquiry.created_at';

    /**
     * @var bool
     */
    protected const SORT_IS_ASCENDING = false;

    /**
     * @var int
     */
    protected const PAGE_NUMBER = 1;

    /**
     * @var int
     */
    protected const PAGE_MAX_PER_PAGE = 3;

    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface $sspInquiryReader
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function provideDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        $companyUserTransfer = $dashboardRequestTransfer->getCompanyUserOrFail();
        $sspInquiryOwnerConditionGroupTransfer = (new SspInquiryOwnerConditionGroupTransfer())
            ->setFkCompanyUser($companyUserTransfer->getIdCompanyUser());

        if ($this->can(ViewCompanySspInquiryPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspInquiryOwnerConditionGroupTransfer->setFkCompany($companyUserTransfer->getFkCompany());
        }

        if ($this->can(ViewBusinessUnitSspInquiryPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspInquiryOwnerConditionGroupTransfer->setFkCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());
        }

        $sspInquiryConditionsTransfer = (new SspInquiryConditionsTransfer())
            ->setFkStore($dashboardRequestTransfer->getStoreOrFail()->getIdStoreOrFail())
            ->setStatus($this->sspInquiryManagementConfig->getPendingStatus())
            ->setSspInquiryOwnerConditionGroup($sspInquiryOwnerConditionGroupTransfer);
        $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())
            ->setSspInquiryConditions($sspInquiryConditionsTransfer)
            ->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage(static::PAGE_MAX_PER_PAGE)
                    ->setPage(static::PAGE_NUMBER),
            );
        $sspInquiryCriteriaTransfer->addSort(
            (new SortTransfer())
                ->setField(static::FIELD_SSP_INQUIRY_CREATED_AT)
                ->setIsAscending(static::SORT_IS_ASCENDING),
        );

        $pendingSspInquiryCollectionTransfer = $this->sspInquiryReader->getSspInquiryCollection($sspInquiryCriteriaTransfer);
        $pendingItemsCount = $pendingSspInquiryCollectionTransfer->getPagination()?->getNbResults();

        $sspInquiryCriteriaTransfer->getSspInquiryConditions()?->setStatus(null);
        $sspInquiryCollectionTransfer = $this->sspInquiryReader->getSspInquiryCollection($sspInquiryCriteriaTransfer);
        $dashboardComponentInquiryTransfer = (new DashboardComponentInquiryTransfer())
            ->setSspInquiryCollection($sspInquiryCollectionTransfer)
            ->setPendingItems($pendingItemsCount);

        return $dashboardResponseTransfer->setDashboardComponentInquiry($dashboardComponentInquiryTransfer);
    }
}
