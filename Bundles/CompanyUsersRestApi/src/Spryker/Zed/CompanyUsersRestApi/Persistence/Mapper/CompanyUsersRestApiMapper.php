<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;

class CompanyUsersRestApiMapper
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection(array $companyUserEntityTransferCollection): CompanyUserCollectionTransfer
    {
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();

        foreach ($companyUserEntityTransferCollection as $companyUserEntityTransfer) {
            $companyUserCollectionTransfer
                ->addCompanyUser($this->mapEntityTransferToCompanyUserTransfer($companyUserEntityTransfer));
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer $companyUserEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapEntityTransferToCompanyUserTransfer(
        SpyCompanyUserEntityTransfer $companyUserEntityTransfer
    ): CompanyUserTransfer {
        return (new CompanyUserTransfer())->fromArray($companyUserEntityTransfer->modifiedToArray(), true);
    }
}
