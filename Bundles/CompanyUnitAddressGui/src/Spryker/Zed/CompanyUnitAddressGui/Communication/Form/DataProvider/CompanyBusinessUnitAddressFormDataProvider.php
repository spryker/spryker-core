<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyBusinessUnitAddressChoiceFormType;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;

class CompanyBusinessUnitAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(
        CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
    ) {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @param int|null $idCompany
     *
     * @return array
     */
    public function getOptions(?int $idCompany = null): array
    {
        return [
            CompanyBusinessUnitAddressChoiceFormType::OPTION_VALUES_ADDRESSES_CHOICES => $this->getAddressChoices($idCompany),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getData(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer
    {
        if (!$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()) {
            $companyBusinessUnitTransfer->setAddressCollection($this->getEmptyAddressCollection());

            return $companyBusinessUnitTransfer;
        }
        $addressCollection = $this->companyUnitAddressFacade->getCompanyUnitAddressCollection(
            $this->prepareCompanyUnitAddressCriteriaFilterTransfer(
                $companyBusinessUnitTransfer->getFkCompany(),
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            )
        );
        $companyBusinessUnitTransfer->setAddressCollection($addressCollection);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param int|null $idCompany
     *
     * @return array
     */
    protected function getAddressChoices(?int $idCompany = null): array
    {
        $addressCollection = $this->companyUnitAddressFacade->getCompanyUnitAddressCollection(
            $this->prepareCompanyUnitAddressCriteriaFilterTransfer($idCompany)
        );

        $result = [];
        foreach ($addressCollection->getCompanyUnitAddresses() as $address) {
            $result[$address->getIdCompanyUnitAddress()] = $address->getAddress1();
        }

        return $result;
    }

    /**
     * @param int|null $idCompany
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer
     */
    protected function prepareCompanyUnitAddressCriteriaFilterTransfer(?int $idCompany = null, ?int $idCompanyBusinessUnit = null): CompanyUnitAddressCriteriaFilterTransfer
    {
        $pagination = new PaginationTransfer();
        $pagination->setMaxPerPage(0);
        $pagination->setPage(1);

        $companyUnitAddressCriteriaFilter = new CompanyUnitAddressCriteriaFilterTransfer();
        $companyUnitAddressCriteriaFilter->setIdCompany($idCompany);
        if ($idCompanyBusinessUnit) {
            $companyUnitAddressCriteriaFilter->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
        }
        $companyUnitAddressCriteriaFilter->setPagination($pagination);

        return $companyUnitAddressCriteriaFilter;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function getEmptyAddressCollection(): CompanyUnitAddressCollectionTransfer
    {
        $labelCollection = new CompanyUnitAddressCollectionTransfer();
        $labelCollection->setCompanyUnitAddresses(new ArrayObject());

        return $labelCollection;
    }
}
