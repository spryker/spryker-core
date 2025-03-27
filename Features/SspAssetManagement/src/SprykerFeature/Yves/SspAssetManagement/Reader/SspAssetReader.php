<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface;
use SprykerFeature\Yves\SspAssetManagement\Permission\SspAssetCustomerPermissionExpanderInterface;
use SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig;
use Symfony\Component\HttpFoundation\Request;

class SspAssetReader implements SspAssetReaderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface $sspAssetManagementClient
     * @param \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig $sspAssetManagementConfig
     * @param \SprykerFeature\Yves\SspAssetManagement\Permission\SspAssetCustomerPermissionExpanderInterface $sspAssetCustomerPermissionExpander
     */
    public function __construct(
        protected SspAssetManagementClientInterface $sspAssetManagementClient,
        protected SspAssetManagementConfig $sspAssetManagementConfig,
        protected SspAssetCustomerPermissionExpanderInterface $sspAssetCustomerPermissionExpander
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(
        Request $request,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionTransfer {
        $sspAssetCriteriaTransfer->setPagination($this->createPaginationTransfer($request));

        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        $this->sspAssetCustomerPermissionExpander->expand(
            $sspAssetCriteriaTransfer,
            $companyUserTransfer,
        );

        if (!$sspAssetCriteriaTransfer->getInclude()) {
            $sspAssetCriteriaTransfer->setInclude(new SspAssetIncludeTransfer());
        }

        $sspAssetCriteriaTransfer->getIncludeOrFail()->setWithCompanyBusinessUnit(true);

        return $this->sspAssetManagementClient->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function createPaginationTransfer(Request $request): PaginationTransfer
    {
        $paginationTransfer = new PaginationTransfer();

        $paginationTransfer->setPage(
            $request->query->getInt($this->sspAssetManagementConfig->getParamPage(), static::DEFAULT_PAGE),
        );
        $paginationTransfer->setMaxPerPage(
            $request->query->getInt($this->sspAssetManagementConfig->getParamPerPage(), $this->sspAssetManagementConfig->getSspAssetCountPerPageList()),
        );

        return $paginationTransfer;
    }
}
