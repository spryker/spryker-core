<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUsersRestApiMapper
{
    /**
     * @param array $companyUsers
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection(array $companyUsers): CompanyUserCollectionTransfer
    {
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();
        foreach ($companyUsers as $companyUser) {
            $companyUserCollectionTransfer
                ->addCompanyUser($this->mapEntityTransferToCompanyUserTransfer($companyUser));
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param array $companyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapEntityTransferToCompanyUserTransfer(
        array $companyUser
    ): CompanyUserTransfer {
        return (new CompanyUserTransfer())->fromArray($companyUser, true);
    }
}
