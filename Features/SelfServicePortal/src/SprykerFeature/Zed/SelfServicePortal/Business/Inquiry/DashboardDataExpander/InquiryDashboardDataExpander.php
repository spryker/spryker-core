<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DashboardDataExpander;

use Generated\Shared\Transfer\DashboardComponentInquiryTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class InquiryDashboardDataExpander implements InquiryDashboardDataExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @var int
     */
    protected const INQUIRY_DASHBOARD_PAGE_NUMBER = 1;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface $sspInquiryReader
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function provideSspInquiryDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        $companyUserTransfer = $dashboardRequestTransfer->getCompanyUserOrFail();
        $sspInquiryOwnerConditionGroupTransfer = (new SspInquiryOwnerConditionGroupTransfer())
            ->setCompanyUser($companyUserTransfer);

        $sspInquiryConditionsTransfer = (new SspInquiryConditionsTransfer())
            ->setStatus($this->selfServicePortalConfig->getInquiryPendingStatus())
            ->setSspInquiryOwnerConditionGroup($sspInquiryOwnerConditionGroupTransfer);

        $sspInquiryConditionsTransfer = $this->addStoreFilter($dashboardRequestTransfer->getStoreOrFail(), $sspInquiryConditionsTransfer);

        $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())
            ->setSspInquiryConditions($sspInquiryConditionsTransfer)
            ->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($this->selfServicePortalConfig->getDashboardInquiryMaxPerPage())
                    ->setPage(static::INQUIRY_DASHBOARD_PAGE_NUMBER),
            );
        $sspInquiryCriteriaTransfer->addSort(
            (new SortTransfer())
                ->setField($this->selfServicePortalConfig->getDefaultInquiryDashboardSortField())
                ->setIsAscending($this->selfServicePortalConfig->isDefaultInquiryDashboardSortAscending()),
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

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryConditionsTransfer
     */
    public function addStoreFilter(StoreTransfer $storeTransfer, SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): SspInquiryConditionsTransfer
    {
        if ($storeTransfer->getIdStore()) {
            return $sspInquiryConditionsTransfer->setIdStore($storeTransfer->getIdStoreOrFail());
        }

        return $sspInquiryConditionsTransfer->setStoreName($storeTransfer->getName());
    }
}
