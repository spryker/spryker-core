<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileAttachFormDataProvider
{
    public function __construct(
        protected CompanyFacadeInterface $companyFacade,
        protected CompanyUserFacadeInterface $companyUserFacade,
        protected CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param array<string, mixed>|null $formData
     *
     * @return array<string, mixed>
     */
    public function getData(?array $formData = null): array
    {
        if (!$formData) {
            return [];
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return array<string, array<string, int>>
     */
    public function getOptions(FileAttachmentTransfer $fileAttachmentTransfer): array
    {
        return [
            AttachFileForm::OPTION_COMPANY_CHOICES => $this->getCompanyChoices($fileAttachmentTransfer),
            AttachFileForm::OPTION_COMPANY_USER_CHOICES => $this->getCompanyUserChoices($fileAttachmentTransfer),
            AttachFileForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices($fileAttachmentTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyChoices(FileAttachmentTransfer $fileAttachmentTransfer): array
    {
        $companyIds = [];

        foreach ($fileAttachmentTransfer->getCompanyCollectionOrFail()->getCompanies() as $companyTransfer) {
            $companyIds[] = $companyTransfer->getIdCompanyOrFail();
        }

        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setCompanyIds($companyIds);

        $companyCollectionTransfer = $this->companyFacade->getCompanyCollection($companyCriteriaFilterTransfer);
        $companyChoices = [];

        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $companyChoices[sprintf('%s (ID: %s)', $companyTransfer->getNameOrFail(), $companyTransfer->getIdCompanyOrFail())] = $companyTransfer->getIdCompanyOrFail();
        }

        return $companyChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyUserChoices(FileAttachmentTransfer $fileAttachmentTransfer): array
    {
        $companyUserIds = [];

        foreach ($fileAttachmentTransfer->getCompanyUserCollectionOrFail()->getCompanyUsers() as $companyUserTransfer) {
            $companyUserIds[] = $companyUserTransfer->getIdCompanyUserOrFail();
        }

        $companyUserCollectionTransfer = $this->companyUserFacade
            ->getCompanyUserCollection((new CompanyUserCriteriaFilterTransfer())
                ->setCompanyUserIds($companyUserIds));

        $companyUserChoices = [];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $customerName = sprintf(
                '%s %s',
                $companyUserTransfer->getCustomerOrFail()->getFirstNameOrFail(),
                $companyUserTransfer->getCustomerOrFail()->getLastNameOrFail(),
            );
            $companyUserChoices[$customerName] = $companyUserTransfer->getIdCompanyUserOrFail();
        }

        return $companyUserChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyBusinessUnitChoices(FileAttachmentTransfer $fileAttachmentTransfer): array
    {
        $companyBusinessUnitIds = [];
        foreach ($fileAttachmentTransfer->getBusinessUnitCollectionOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection((new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setCompanyBusinessUnitIds($companyBusinessUnitIds));

        $companyBusinessUnitChoices = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitChoices[sprintf('%s (ID: %s)', $companyBusinessUnitTransfer->getNameOrFail(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail())] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $companyBusinessUnitChoices;
    }

    /**
     * @param string $searchTerm
     *
     * @return array<string, array<int, array<string, int|string>>>
     */
    public function getCompanyAutocompleteData(string $searchTerm): array
    {
        $filterTransfer = (new FilterTransfer())
            ->setLimit($this->selfServicePortalConfig->getCompanyFileAutocompleteLimit());
        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setFilter($filterTransfer)
            ->setName($searchTerm);
        $companies = $this->companyFacade->getCompanyCollection($companyCriteriaFilterTransfer);
        $result = [];

        foreach ($companies->getCompanies() as $companyTransfer) {
            $result['results'][] = [
                'id' => $companyTransfer->getIdCompanyOrFail(),
                'text' => sprintf('%s (ID: %s)', $companyTransfer->getNameOrFail(), $companyTransfer->getIdCompanyOrFail()),
            ];
        }

        return $result;
    }

    /**
     * @param string $searchTerm
     *
     * @return array<string, array<int, array<string, int|string>>>
     */
    public function getCompanyUserAutocompleteData(string $searchTerm): array
    {
        $filterTransfer = (new FilterTransfer())
            ->setLimit($this->selfServicePortalConfig->getCompanyFileAutocompleteLimit());
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setFilter($filterTransfer)
            ->setCustomerName($searchTerm);
        $companyUsers = $this->companyUserFacade->getCompanyUserCollection($companyUserCriteriaFilterTransfer);
        $result = [];

        foreach ($companyUsers->getCompanyUsers() as $companyUserTransfer) {
            $result['results'][] = [
                'id' => $companyUserTransfer->getIdCompanyUserOrFail(),
                'text' => $companyUserTransfer->getCustomerOrFail()->getFirstNameOrFail() . ' ' . $companyUserTransfer->getCustomerOrFail()->getLastNameOrFail(),
            ];
        }

        return $result;
    }

    /**
     * @param string $searchTerm
     *
     * @return array<string, array<int, array<string, int|string>>>
     */
    public function getCompanyBusinessUnitAutocompleteData(string $searchTerm): array
    {
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit($this->selfServicePortalConfig->getCompanyFileAutocompleteLimit()))
            ->setName($searchTerm);
        $companyBusinessUnits = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        $result = [];

        foreach ($companyBusinessUnits->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $result['results'][] = [
                'id' => $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
                'text' => sprintf('%s (ID: %s)', $companyBusinessUnitTransfer->getNameOrFail(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail()),
            ];
        }

        return $result;
    }

    /**
     * @param array<int> $companyIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyTransfer>
     */
    public function getCompanyCollectionByIds(array $companyIds): array
    {
        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setCompanyIds($companyIds);

        return $this->companyFacade->getCompanyCollection($companyCriteriaFilterTransfer)
            ->getCompanies()
            ->getArrayCopy();
    }

    /**
     * @param array<int> $companyUserIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyUserTransfer>
     */
    public function getCompanyUserCollectionByIds(array $companyUserIds): array
    {
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setCompanyUserIds($companyUserIds);

        return $this->companyUserFacade->getCompanyUserCollection($companyUserCriteriaFilterTransfer)
            ->getCompanyUsers()
            ->getArrayCopy();
    }

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function getCompanyBusinessUnitCollectionByIds(array $companyBusinessUnitIds): array
    {
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setCompanyBusinessUnitIds($companyBusinessUnitIds);

        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
            ->getCompanyBusinessUnits()
            ->getArrayCopy();
    }
}
