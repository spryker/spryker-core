<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DashboardDataExpander;

use Generated\Shared\Transfer\DashboardComponentServicesTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class ServiceDashboardDataExpander implements ServiceDashboardDataExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @var int
     */
    protected const SERVICE_DASHBOARD_PAGE_NUMBER = 1;

    /**
     * @var string
     */
    protected const SORT_FIELD_SCHEDULED_AT = 'scheduled_at';

    public function __construct(
        protected ServiceReaderInterface $serviceReader,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    public function provideSspServiceDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        $companyUserTransfer = $dashboardRequestTransfer->getCompanyUserOrFail();
        $customerTransfer = $dashboardRequestTransfer->getCustomerOrFail();
        $withServicesCount = $dashboardRequestTransfer->getWithServicesCount();
        $companyUserTransfer->setCustomer($customerTransfer);

        $sspServiceConditionsTransfer = (new SspServiceConditionsTransfer())
            ->setCompanyUuid($companyUserTransfer->getCompanyOrFail()->getUuid())
            ->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuid())
            ->setCustomerReference($dashboardRequestTransfer->getCustomerOrFail()->getCustomerReference());

        $sspServiceCriteriaTransfer = (new SspServiceCriteriaTransfer())
            ->setServiceConditions($sspServiceConditionsTransfer)
            ->setCompanyUser($companyUserTransfer);

        if ($withServicesCount) {
            $sspServiceCriteriaTransfer->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($withServicesCount)
                    ->setPage(static::SERVICE_DASHBOARD_PAGE_NUMBER),
            );
        }

        $sspServiceCriteriaTransfer->addSort(
            (new SortTransfer())
                ->setField(static::SORT_FIELD_SCHEDULED_AT)
                ->setIsAscending(false),
        );

        $sspServiceCollectionTransfer = $this->serviceReader->getSspServiceCollection($sspServiceCriteriaTransfer);
        $pendingItemsCount = $sspServiceCollectionTransfer->getPagination()?->getNbResults() ?? 0;

        $dashboardComponentServicesTransfer = (new DashboardComponentServicesTransfer())
            ->setSspServiceCollection($sspServiceCollectionTransfer)
            ->setPendingItems($pendingItemsCount);

        return $dashboardResponseTransfer->setDashboardComponentServices($dashboardComponentServicesTransfer);
    }
}
