<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyUnitAddress implements CompanyUnitAddressInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface
     */
    protected $companyBusinessUnitAddressReader;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface
     */
    protected $companyUnitAddressPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface $countryFacade
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader
     * @param \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface $companyUnitAddressPluginExecutor
     */
    public function __construct(
        CompanyUnitAddressEntityManagerInterface $entityManager,
        CompanyUnitAddressToCountryFacadeInterface $countryFacade,
        CompanyUnitAddressToLocaleFacadeInterface $localeFacade,
        CompanyUnitAddressToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader,
        CompanyUnitAddressPluginExecutorInterface $companyUnitAddressPluginExecutor
    ) {
        $this->entityManager = $entityManager;
        $this->countryFacade = $countryFacade;
        $this->localeFacade = $localeFacade;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyBusinessUnitAddressReader = $companyBusinessUnitAddressReader;
        $this->companyUnitAddressPluginExecutor = $companyUnitAddressPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function create(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUnitAddressTransfer) {
            return $this->executeSaveCompanyUnitAddressTransaction($companyUnitAddressTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function update(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUnitAddressTransfer) {
            return $this->executeSaveCompanyUnitAddressTransaction($companyUnitAddressTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($companyUnitAddressTransfer) {
            $this->executeDeleteCompanyUnitAddressTransaction($companyUnitAddressTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return int
     */
    protected function retrieveIdCountry(CompanyUnitAddressTransfer $companyUnitAddressTransfer): int
    {
        $idCountry = $companyUnitAddressTransfer->getFkCountry();
        if (empty($idCountry)) {
            $iso2Code = $companyUnitAddressTransfer->getIso2Code();
            if (empty($iso2Code) === false) {
                $countryTransfer = $this->countryFacade->getCountryByIso2Code($iso2Code);
                $idCountry = $countryTransfer->getIdCountry();
            } else {
                $idCountry = $this->getCompanyCountryId();
            }
        }

        return $idCountry;
    }

    /**
     * @return string
     */
    protected function getIsoCode(): string
    {
        $localeName = $this->localeFacade->getCurrentLocale()
            ->getLocaleName();

        return explode('_', $localeName)[1];
    }

    /**
     * @return int
     */
    protected function getCompanyCountryId(): int
    {
        $countryTransfer = $this->countryFacade->getCountryByIso2Code($this->getIsoCode());

        return $countryTransfer->getIdCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function getCompanyUnitAddressCollectionByIdCompanyBusinessUnit(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyUnitAddressCollectionTransfer
    {
        return $this->companyBusinessUnitAddressReader->getCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function updateBusinessUnitDefaultAddresses(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): void {
        $companyUnitAddressTransfer->requireIdCompanyUnitAddress();

        if ($companyUnitAddressTransfer->getFkCompanyBusinessUnit()
            && $companyUnitAddressTransfer->getIsDefaultBilling()
        ) {
            $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
            $companyBusinessUnitTransfer
                ->setIdCompanyBusinessUnit($companyUnitAddressTransfer->getFkCompanyBusinessUnit())
                ->setDefaultBillingAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

            $companyUnitAddressCollectionTransfer = $this->getCompanyUnitAddressCollectionByIdCompanyBusinessUnit($companyBusinessUnitTransfer);
            $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollectionTransfer);

            $this->companyBusinessUnitFacade->update($companyBusinessUnitTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    protected function executeSaveCompanyUnitAddressTransaction(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        $idCountry = $this->retrieveIdCountry($companyUnitAddressTransfer);
        $companyUnitAddressTransfer->setFkCountry($idCountry);
        $isDefaultBilling = $companyUnitAddressTransfer->getIsDefaultBilling();
        $companyUnitAddressExist = $companyUnitAddressTransfer->getIdCompanyUnitAddress() !== null;
        $companyUnitAddressTransfer = $this->entityManager->saveCompanyUnitAddress($companyUnitAddressTransfer);

        if (!$companyUnitAddressExist) {
            $this->createAddressToBusinessUnitRelations($companyUnitAddressTransfer, $companyUnitAddressTransfer->getIdCompanyUnitAddress());
        }

        $companyUnitAddressTransfer->setIsDefaultBilling($isDefaultBilling);
        $this->updateBusinessUnitDefaultAddresses($companyUnitAddressTransfer);

        $companyUnitAddressTransfer = $this->companyUnitAddressPluginExecutor
            ->executePostSavePlugins($companyUnitAddressTransfer);

        return (new CompanyUnitAddressResponseTransfer())->setIsSuccessful(true)
            ->setCompanyUnitAddressTransfer($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function executeDeleteCompanyUnitAddressTransaction(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): void {
        $companyUnitAddressTransfer->requireIdCompanyUnitAddress();

        $this->entityManager->deleteCompanyUnitAddressById(
            $companyUnitAddressTransfer->getIdCompanyUnitAddress()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param int $idCompanyUnitAddress
     *
     * @return void
     */
    protected function createAddressToBusinessUnitRelations(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        int $idCompanyUnitAddress
    ): void {
        $businessUnits = $companyUnitAddressTransfer->getCompanyBusinessUnits();

        if (!$businessUnits) {
            return;
        }

        foreach ($businessUnits->getCompanyBusinessUnits() as $companyBusinessUnit) {
            $entityTransfer = new SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer();
            $entityTransfer
                ->setFkCompanyBusinessUnit($companyBusinessUnit->getIdCompanyBusinessUnit())
                ->setFkCompanyUnitAddress($idCompanyUnitAddress);
            $this->entityManager->saveAddressToBusinessUnitRelation($entityTransfer);
        }
    }
}
