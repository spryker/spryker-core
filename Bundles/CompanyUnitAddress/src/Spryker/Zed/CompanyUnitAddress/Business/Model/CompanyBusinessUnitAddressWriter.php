<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface;

class CompanyBusinessUnitAddressWriter implements CompanyBusinessUnitAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface
     */
    protected $companyBusinessUnitAddressReader;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface $entityManager
     */
    public function __construct(
        CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader,
        CompanyUnitAddressEntityManagerInterface $entityManager
    ) {
        $this->companyBusinessUnitAddressReader = $companyBusinessUnitAddressReader;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function saveCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void {
        $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();
        $currentAddresses = $this->getCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);
        $requestedAddresses = $this->getRequestedCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);
        $saveAddresses = array_diff($requestedAddresses, $currentAddresses);
        $deleteAddresses = array_diff($currentAddresses, $requestedAddresses);
        $this->assignToCompanyBusinessUnit(
            $saveAddresses,
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );
        $this->unAssignFromCompanyBusinessUnit(
            $deleteAddresses,
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );
    }

    /**
     * @param array $saveAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    protected function assignToCompanyBusinessUnit(
        array $saveAddresses,
        int $idCompanyBusinessUnit
    ): void {
        $this->entityManager->assignToCompanyBusinessUnit($saveAddresses, $idCompanyBusinessUnit);
    }

    /**
     * @param array $deleteAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    protected function unAssignFromCompanyBusinessUnit(
        array $deleteAddresses,
        int $idCompanyBusinessUnit
    ): void {
        $this->entityManager->unAssignFromCompanyBusinessUnit($deleteAddresses, $idCompanyBusinessUnit);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return array
     */
    protected function getCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): array {
        $idAddresses = [];

        $addressCollection = $this->companyBusinessUnitAddressReader->getCompanyBusinessUnitAddresses(
            $companyBusinessUnitTransfer
        );

        foreach ($addressCollection->getCompanyUnitAddresses() as $companyUnitAddress) {
            $idAddresses[] = $companyUnitAddress->getIdCompanyUnitAddress();
        }

        return $idAddresses;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return array
     */
    protected function getRequestedCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): array {
        $idAddresses = [];

        if ($companyBusinessUnitTransfer->getAddressCollection() === null) {
            return $idAddresses;
        }

        $companyUnitAddresses = $companyBusinessUnitTransfer->getAddressCollection()
            ->getCompanyUnitAddresses();
        foreach ($companyUnitAddresses as $companyUnitAddress) {
            $idAddresses[] = $companyUnitAddress->getIdCompanyUnitAddress();
        }

        return $idAddresses;
    }
}
