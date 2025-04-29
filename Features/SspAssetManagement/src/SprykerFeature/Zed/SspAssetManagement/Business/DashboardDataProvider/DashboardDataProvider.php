<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Zed\SspAssetManagement\Business\DashboardDataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\DashboardComponentAssetsTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Zed\SspAssetManagement\Business\Reader\SspAssetReaderInterface;

class DashboardDataProvider implements DashboardDataProviderInterface
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const SORT_CREATED_AT = 'createdDate';

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
    protected const PAGE_MAX_PER_PAGE = 10;

    /**
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\Reader\SspAssetReaderInterface $assetReader
     */
    public function __construct(protected SspAssetReaderInterface $assetReader)
    {
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
        if (
            !$this->can(ViewCompanySspAssetPermissionPlugin::KEY, $dashboardRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail())
            && !$this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $dashboardRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail())
        ) {
            return $dashboardResponseTransfer;
        }

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions(
                (new SspAssetConditionsTransfer()),
            )
            ->setInclude(
                (new SspAssetIncludeTransfer())
                    ->setWithImageFile(true),
            )
            ->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage(static::PAGE_MAX_PER_PAGE)
                    ->setPage(static::PAGE_NUMBER),
            )
            ->addSort(
                (new SortTransfer())
                    ->setField(static::SORT_CREATED_AT)
                    ->setIsAscending(static::SORT_IS_ASCENDING),
            );

        $this->expandWithPermissions($sspAssetCriteriaTransfer, $dashboardRequestTransfer->getCompanyUserOrFail());

        $sspAssetCollectionTransfer = $this->assetReader->getSspAssetCollection($sspAssetCriteriaTransfer);

        $sspAssetCollectionTransfer = (new DashboardComponentAssetsTransfer())
            ->setSspAssetCollection($sspAssetCollectionTransfer);

        return $dashboardResponseTransfer->setDashboardComponentAssets($sspAssetCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    public function expandWithPermissions(
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCriteriaTransfer {
        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        if ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setAssignedBusinessUnitCompanyId($companyUserTransfer->getFkCompanyOrFail());

            return $sspAssetCriteriaTransfer;
        }

        if ($this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setAssignedBusinessUnitId($companyUserTransfer->getFkCompanyBusinessUnitOrFail());
        }

        return $sspAssetCriteriaTransfer;
    }
}
