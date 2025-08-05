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
    public function validateRequestGrantedToCreateAsset(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionResponseTransfer;

    public function validateAssetTransfer(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): bool;

    public function isAssetUpdateGranted(
        SspAssetTransfer $sspAssetTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): bool;

    public function isCompanyUserGrantedToApplyCriteria(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): bool;
}
