<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function validateRequestGrantedToCreateAsset(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return bool
     */
    public function validateAssetTransfer(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return bool
     */
    public function isAssetUpdateGranted(
        SspAssetTransfer $sspAssetTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return bool
     */
    public function isCompanyUserGrantedToApplyCriteria(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): bool;
}
