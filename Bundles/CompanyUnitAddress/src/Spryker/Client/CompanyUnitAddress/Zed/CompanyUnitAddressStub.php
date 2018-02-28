<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUnitAddress\Zed;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Client\CompanyUnitAddress\Dependency\Client\CompanyUnitAddressToZedRequestClientInterface;

class CompanyUnitAddressStub implements CompanyUnitAddressStubInterface
{
    /**
     * @var \Spryker\Client\CompanyUnitAddress\Dependency\Client\CompanyUnitAddressToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CompanyUnitAddress\Dependency\Client\CompanyUnitAddressToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CompanyUnitAddressToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->zedRequestClient->call('/company-unit-address/gateway/create', $companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->zedRequestClient->call('/company-unit-address/gateway/update', $companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function deleteCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->zedRequestClient->call('/company-unit-address/gateway/delete', $companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        return $this->zedRequestClient->call(
            '/company-unit-address/gateway/get-company-unit-address-collection',
            $criteriaFilterTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        return $this->zedRequestClient->call('/company-unit-address/gateway/get-company-unit-address-by-id', $companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCompanyUnitAddressAndUpdateBusinessUnitDefaultAddresses(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->zedRequestClient->call(
            '/company-unit-address/gateway/create-company-unit-address-and-update-business-unit-default-addresses',
            $companyUnitAddressTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateCompanyUnitAddressAndBusinessUnitDefaultAddresses(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->zedRequestClient->call(
            '/company-unit-address/gateway/update-company-unit-address-and-business-unit-default-addresses',
            $companyUnitAddressTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function saveCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void {
        $this->zedRequestClient->call(
            '/company-unit-address/gateway/save-company-business-unit-addresses',
            $companyBusinessUnitTransfer
        );
    }
}
