<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\Request;

class SspAssetReader implements SspAssetReaderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    public function getSspAssetCollection(
        Request $request,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionTransfer {
        $sspAssetCriteriaTransfer->setPagination($this->createPaginationTransfer($request));

        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        $sspAssetCriteriaTransfer->setCompanyUser($companyUserTransfer);

        if (!$sspAssetCriteriaTransfer->getInclude()) {
            $sspAssetCriteriaTransfer->setInclude(new SspAssetIncludeTransfer());
        }

        $sspAssetCriteriaTransfer->getIncludeOrFail()->setWithOwnerCompanyBusinessUnit(true);

        $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setStatuses(
            $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_VIEW),
        );

        return $this->selfServicePortalClient->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    protected function createPaginationTransfer(Request $request): PaginationTransfer
    {
        $paginationTransfer = new PaginationTransfer();

        $paginationTransfer->setPage(
            $request->query->getInt($this->selfServicePortalConfig->getSspAssetParamPage(), static::DEFAULT_PAGE),
        );
        $paginationTransfer->setMaxPerPage(
            $request->query->getInt($this->selfServicePortalConfig->getSspAssetParamPerPage(), $this->selfServicePortalConfig->getSspAssetCountPerPageList()),
        );

        return $paginationTransfer;
    }

    /**
     * @param string $allowedAction
     *
     * @return array<string>
     */
    protected function getStatusesByAllowedAction(string $allowedAction): array
    {
        $statuses = [];

        foreach ($this->selfServicePortalConfig->getSspStatusAllowedActionsMapping() as $status => $allowedActions) {
            if (in_array($allowedAction, $allowedActions)) {
                $statuses[] = $status;
            }
        }

        return $statuses;
    }
}
