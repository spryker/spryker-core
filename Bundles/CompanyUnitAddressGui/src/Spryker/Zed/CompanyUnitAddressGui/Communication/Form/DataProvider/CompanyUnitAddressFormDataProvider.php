<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyUnitAddressForm;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface;

class CompanyUnitAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     * @param \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface $countryFacade
     */
    public function __construct(
        CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade,
        CompanyUnitAddressGuiToCompanyFacadeInterface $companyFacade,
        CompanyUnitAddressGuiToCountryFacadeInterface $countryFacade
    ) {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
        $this->companyFacade = $companyFacade;
        $this->countryFacade = $countryFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CompanyUnitAddressTransfer::class,
            CompanyUnitAddressForm::OPTION_COMPANY_CHOICES => $this->prepareCompanyChoices(),
            CompanyUnitAddressForm::OPTION_COUNTRY_CHOICES => $this->prepareCountryChoices(),
        ];
    }

    /**
     * @param int|null $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getData(?int $idCompanyUnitAddress = null)
    {
        if (!$idCompanyUnitAddress) {
            $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();

            return $companyUnitAddressTransfer;
        }

        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->companyUnitAddressFacade
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }

    /**
     * @return array
     */
    protected function prepareCompanyChoices(): array
    {
        $result = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $company) {
            $result[$company->getIdCompany()] = $company->getName();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function prepareCountryChoices(): array
    {
        $result = [];

        foreach ($this->countryFacade->getAvailableCountries()->getCountries() as $country) {
            $result[$country->getIdCountry()] = $country->getName();
        }

        return $result;
    }
}
