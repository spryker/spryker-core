<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DashboardDataExpander;

use Generated\Shared\Transfer\DashboardComponentAssetsTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;

class SspAssetSspAssetDashboardDataExpander implements SspAssetDashboardDataExpanderInterface
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

    public function __construct(
        protected SspAssetReaderInterface $assetReader
    ) {
    }

    public function provideSspAssetDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
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
                    ->setMaxPerPage($dashboardRequestTransfer->getWithSspAssetCountOrFail())
                    ->setPage(static::PAGE_NUMBER),
            )
            ->addSort(
                (new SortTransfer())
                    ->setField(static::SORT_CREATED_AT)
                    ->setIsAscending(static::SORT_IS_ASCENDING),
            )
            ->setCompanyUser($dashboardRequestTransfer->getCompanyUserOrFail());

        $sspAssetCollectionTransfer = $this->assetReader->getSspAssetCollection($sspAssetCriteriaTransfer);

        $sspAssetCollectionTransfer = (new DashboardComponentAssetsTransfer())
            ->setSspAssetCollection($sspAssetCollectionTransfer);

        return $dashboardResponseTransfer->setDashboardComponentAssets($sspAssetCollectionTransfer);
    }
}
