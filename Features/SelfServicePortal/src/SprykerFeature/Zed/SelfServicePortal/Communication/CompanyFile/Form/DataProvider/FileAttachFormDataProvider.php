<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileAttachFormDataProvider
{
    /**
     * @param \Spryker\Zed\Company\Business\CompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
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
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, array<string, int>>
     */
    public function getOptions(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        return [
            AttachFileForm::OPTION_COMPANY_CHOICES => $this->getCompanyChoices($fileAttachmentCollectionTransfer),
            AttachFileForm::OPTION_COMPANY_USER_CHOICES => $this->getCompanyUserChoices($fileAttachmentCollectionTransfer),
            AttachFileForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices($fileAttachmentCollectionTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyChoices(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        $companyIds = [];
        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
            if ($fileAttachmentTransfer->getEntityName() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY) {
                $companyIds[] = $fileAttachmentTransfer->getEntityIdOrFail();
            }
        }
        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setCompanyIds($companyIds);

        $companyCollectionTransfer = $this->companyFacade->getCompanyCollection($companyCriteriaFilterTransfer);
        $companyChoices = [];

        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $companyChoices[$companyTransfer->getNameOrFail()] = $companyTransfer->getIdCompanyOrFail();
        }

        return $companyChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyUserChoices(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        $companyUserIds = [];
        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
            if ($fileAttachmentTransfer->getEntityName() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER) {
                $companyUserIds[] = $fileAttachmentTransfer->getEntityIdOrFail();
            }
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
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, int>
     */
    protected function getCompanyBusinessUnitChoices(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        $companyBusinessUnitIds = [];
        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
            if ($fileAttachmentTransfer->getEntityName() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT) {
                $companyBusinessUnitIds[] = $fileAttachmentTransfer->getEntityIdOrFail();
            }
        }
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection((new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setCompanyBusinessUnitIds($companyBusinessUnitIds));

        $companyBusinessUnitChoices = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitChoices[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
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
            ->setLimit($this->selfServicePortalConfig->getFileAttachmentFormAutocompleteLimit());
        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setFilter($filterTransfer)
            ->setName($searchTerm);
        $companies = $this->companyFacade->getCompanyCollection($companyCriteriaFilterTransfer);
        $result = [];

        foreach ($companies->getCompanies() as $companyTransfer) {
            $result['results'][] = [
                'id' => $companyTransfer->getIdCompanyOrFail(),
                'text' => $companyTransfer->getNameOrFail(),
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
            ->setLimit($this->selfServicePortalConfig->getFileAttachmentFormAutocompleteLimit());
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
            ->setFilter((new FilterTransfer())->setLimit($this->selfServicePortalConfig->getFileAttachmentFormAutocompleteLimit()))
            ->setName($searchTerm);
        $companyBusinessUnits = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        $result = [];

        foreach ($companyBusinessUnits->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $result['results'][] = [
                'id' => $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
                'text' => $companyBusinessUnitTransfer->getNameOrFail(),
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
