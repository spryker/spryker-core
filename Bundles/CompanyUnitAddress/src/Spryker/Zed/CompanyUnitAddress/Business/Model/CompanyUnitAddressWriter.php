<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface;

class CompanyUnitAddressWriter implements CompanyUnitAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface
     */
    protected $companyUnitAddressWriterRepository;

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
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface $companyUnitAddressWriterRepository
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface $countryFacade
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        CompanyUnitAddressWriterRepositoryInterface $companyUnitAddressWriterRepository,
        CompanyUnitAddressToCountryFacadeInterface $countryFacade,
        CompanyUnitAddressToLocaleFacadeInterface $localeFacade,
        CompanyUnitAddressToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->companyUnitAddressWriterRepository = $companyUnitAddressWriterRepository;
        $this->countryFacade = $countryFacade;
        $this->localeFacade = $localeFacade;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function create(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->save($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function update(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->save($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $this->companyUnitAddressWriterRepository->delete($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    protected function save(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        $fkCountry = $this->retrieveFkCountry($companyUnitAddressTransfer);
        $companyUnitAddressTransfer->setFkCountry($fkCountry);
        $isDefaultBilling = $companyUnitAddressTransfer->getIsDefaultBilling();
        $companyUnitAddressTransfer = $this->companyUnitAddressWriterRepository->save($companyUnitAddressTransfer);
        $companyUnitAddressTransfer->setIsDefaultBilling($isDefaultBilling);
        $this->updateBusinessUnitDefaultAddresses($companyUnitAddressTransfer);
        $companyUnitAddressResponseTransfer = new CompanyUnitAddressResponseTransfer();
        $companyUnitAddressResponseTransfer->setCompanyUnitAddressTransfer($companyUnitAddressTransfer);
        $companyUnitAddressResponseTransfer->setIsSuccessful(true);

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return int
     */
    protected function retrieveFkCountry(CompanyUnitAddressTransfer $companyUnitAddressTransfer): int
    {
        $fkCountry = $companyUnitAddressTransfer->getFkCountry();
        if (empty($fkCountry)) {
            $iso2Code = $companyUnitAddressTransfer->getIso2Code();
            if (empty($iso2Code) === false) {
                $countryTransfer = $this->countryFacade->getCountryByIso2Code($iso2Code);
                $fkCountry = $countryTransfer->getIdCountry();
            } else {
                $fkCountry = $this->getCompanyCountryId();
            }
        }

        return $fkCountry;
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
            $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($companyUnitAddressTransfer->getFkCompanyBusinessUnit())
                ->setDefaultBillingAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

            $this->companyBusinessUnitFacade->update($companyBusinessUnitTransfer);
        }
    }
}
