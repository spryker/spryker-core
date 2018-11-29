<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyUsersResourceMapper implements CompanyUsersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): RestCompanyUserAttributesTransfer {
        $restCompanyBusinessUnitAttributesTransfer = $this->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
            $companyUserTransfer->getCompanyBusinessUnit()
        );

        $restCompanyAttributesTransfer = $this->mapCompanyTransferToRestCompanyAttributesTransfer(
            $companyUserTransfer->getCompanyBusinessUnit()->getCompany()
        );

        $restCompanyRoleAttributesTransferCollection = $this->mapCompanyRoleCollectionToRestCompanyRoleAttributesTransfer(
            $companyUserTransfer->getCompanyRoleCollection()
        );

        return (new RestCompanyUserAttributesTransfer())
            ->setIsActive($companyUserTransfer->getIsActive())
            ->setCompanyBusinessUnit($restCompanyBusinessUnitAttributesTransfer)
            ->setCompany($restCompanyAttributesTransfer)
            ->setCompanyRoles($restCompanyRoleAttributesTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer[]
     */
    protected function mapCompanyRoleCollectionToRestCompanyRoleAttributesTransfer(
        CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
    ): ArrayObject {
        $restCompanyRoleAttributesTransferCollection = new ArrayObject();

        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $restCompanyRoleAttributesTransferCollection->append(
                (new RestCompanyRoleAttributesTransfer())->fromArray($companyRoleTransfer->toArray(), true)
            );
        }

        return $restCompanyRoleAttributesTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        $restCompanyUnitAddressAttributesTransferCollection = $this->mapCompanyUnitAddressCollectionTransferToRestCompanyUnitAddressAttributesTransfer(
            $companyBusinessUnitTransfer->getAddressCollection()
        );

        $restCompanyAttributesTransfer = $this->mapCompanyTransferToRestCompanyAttributesTransfer(
            $companyBusinessUnitTransfer->getCompany()
        );

        return (new RestCompanyBusinessUnitAttributesTransfer())
            ->fromArray($companyBusinessUnitTransfer->toArray(), true)
            ->setBillingAddresses($restCompanyUnitAddressAttributesTransferCollection)
            ->setCompany($restCompanyAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyAttributesTransfer
     */
    protected function mapCompanyTransferToRestCompanyAttributesTransfer(
        CompanyTransfer $companyTransfer
    ): RestCompanyAttributesTransfer {
        return (new RestCompanyAttributesTransfer())->fromArray($companyTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer[]
     */
    protected function mapCompanyUnitAddressCollectionTransferToRestCompanyUnitAddressAttributesTransfer(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): ArrayObject {
        $restCompanyUnitAddressAttributesTransferCollection = new ArrayObject();

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $restCompanyUnitAddressAttributesTransferCollection->append(
                (new RestCompanyUnitAddressAttributesTransfer())->fromArray($companyUnitAddressTransfer->toArray(), true)
            );
        }

        return $restCompanyUnitAddressAttributesTransferCollection;
    }
}
