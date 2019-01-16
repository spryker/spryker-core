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
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function createCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call('/company-unit-address/gateway/create', $companyUnitAddressTransfer);

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function updateCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call('/company-unit-address/gateway/update', $companyUnitAddressTransfer);

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function deleteCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call('/company-unit-address/gateway/delete', $companyUnitAddressTransfer);

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer */
        $companyUnitAddressCollectionTransfer = $this->zedRequestClient->call(
            '/company-unit-address/gateway/get-company-unit-address-collection',
            $criteriaFilterTransfer
        );

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $this->zedRequestClient->call('/company-unit-address/gateway/get-company-unit-address-by-id', $companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function createCompanyUnitAddressAndUpdateBusinessUnitDefaultAddresses(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call(
            '/company-unit-address/gateway/create-company-unit-address-and-update-business-unit-default-addresses',
            $companyUnitAddressTransfer
        );

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function updateCompanyUnitAddressAndBusinessUnitDefaultAddresses(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call(
            '/company-unit-address/gateway/update-company-unit-address-and-business-unit-default-addresses',
            $companyUnitAddressTransfer
        );

        return $companyUnitAddressResponseTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function findCompanyBusinessUnitAddressByUuid(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer */
        $companyUnitAddressResponseTransfer = $this->zedRequestClient->call(
            '/company-unit-address/gateway/find-company-business-unit-address-by-uuid',
            $companyUnitAddressTransfer
        );

        return $companyUnitAddressResponseTransfer;
    }
}
