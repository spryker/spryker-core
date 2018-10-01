<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;

class CompanyUserMapper implements CompanyUserMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer
     */
    public function mapCompanyUserTransferToEntityTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): SpyCompanyUserEntityTransfer {
        $companyUserEntityTransfer = new SpyCompanyUserEntityTransfer();
        $data = $companyUserTransfer->modifiedToArray();
        unset($data['customer']);
        $companyUserEntityTransfer->fromArray($data, true);

        return $companyUserEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer $companyUserEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapEntityTransferToCompanyUserTransfer(
        SpyCompanyUserEntityTransfer $companyUserEntityTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer = (new CompanyUserTransfer())->fromArray($companyUserEntityTransfer->modifiedToArray(), true);

        if ($companyUserEntityTransfer->getCustomer()) {
            $customerTransfer = (new CustomerTransfer())->fromArray(
                $companyUserEntityTransfer->getCustomer()->modifiedToArray(),
                true
            );
            $companyUserTransfer->setCustomer($customerTransfer);
        }

        if ($companyUserEntityTransfer->getCompany()) {
            $companyTransfer = (new CompanyTransfer())->fromArray(
                $companyUserEntityTransfer->getCompany()->modifiedToArray(),
                true
            );
            $companyUserTransfer->setCompany($companyTransfer);
        }

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection($collection): CompanyUserCollectionTransfer
    {
        $companyUsers = new ArrayObject();
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();

        foreach ($collection as $companyUserEntityTransfer) {
            $companyUsers->append($this->mapEntityTransferToCompanyUserTransfer($companyUserEntityTransfer));
        }

        $companyUserCollectionTransfer->setCompanyUsers($companyUsers);

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserEntityToCompanyUserTransfer(
        SpyCompanyUser $companyUser
    ): CompanyUserTransfer {
        return (new CompanyUserTransfer())->fromArray(
            $companyUser->toArray(),
            true
        );
    }
}
