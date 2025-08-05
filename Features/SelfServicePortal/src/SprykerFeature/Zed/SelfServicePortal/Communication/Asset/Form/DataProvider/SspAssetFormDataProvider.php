<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspAssetFormDataProvider
{
    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_VIEW_IMAGE
     *
     * @var string
     */
    protected const ROUTE_NAME_ASSET_VIEW_IMAGE = 'asset-image';

    public function __construct(
        protected SelfServicePortalFacadeInterface $sspAssetManagementFacade,
        protected SelfServicePortalConfig $config,
        protected CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        protected CompanyFacadeInterface $companyFacade
    ) {
    }

    public function getData(int $sspAssetId): ?SspAssetTransfer
    {
        $sspAssetCollectionTransfer = $this->sspAssetManagementFacade->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())
                ->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())
                        ->addIdSspAsset($sspAssetId),
                )
                ->setInclude(
                    (new SspAssetIncludeTransfer())
                        ->setWithOwnerCompanyBusinessUnit(true)
                        ->setWithAssignedBusinessUnits(true),
                ),
        );

        if ($sspAssetCollectionTransfer->getSspAssets()->count() === 0) {
            return null;
        }

        return $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(SspAssetTransfer $sspAssetTransfer): array
    {
        $assignedBusinessUnits = $this->getAssignedBusinessUnits($sspAssetTransfer->getBusinessUnitAssignments());
        $assignedCompanies = $this->getAssignedCompanies($sspAssetTransfer->getBusinessUnitAssignments());

        $companyBusinessUnitOwnerTransfer = $sspAssetTransfer->getCompanyBusinessUnit();

        return [
            SspAssetForm::OPTION_ORIGINAL_IMAGE_URL => $this->getAssetImageUrl($sspAssetTransfer),
            SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS => $assignedCompanies,
            SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => $assignedBusinessUnits,
            SspAssetForm::OPTION_STATUS_OPTIONS => array_flip($this->config->getAssetStatuses()),
            SspAssetForm::OPTION_BUSINESS_UNIT_OWNER => $companyBusinessUnitOwnerTransfer ? [$companyBusinessUnitOwnerTransfer->getNameOrFail() => $companyBusinessUnitOwnerTransfer->getIdCompanyBusinessUnitOrFail()] : [],
        ];
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $submittedFormData
     *
     * @return array<string, mixed>
     */
    public function expandOptionsWithSubmittedData(array $options, array $submittedFormData): array
    {
        $assignedBusinessUnitIds = [];
        if (isset($submittedFormData[SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS])) {
            $assignedBusinessUnitIds = array_map('intval', $submittedFormData[SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS]);
        }

        $businessUnitOwnerId = null;
        if (isset($submittedFormData[SspAssetForm::FIELD_BUSINESS_UNIT_OWNER])) {
            $businessUnitOwnerId = $submittedFormData[SspAssetForm::FIELD_BUSINESS_UNIT_OWNER];
            $businessUnitOwnerId = in_array($businessUnitOwnerId, $assignedBusinessUnitIds) ? $businessUnitOwnerId : null;
        }

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setCompanyBusinessUnitIds($assignedBusinessUnitIds),
        );

        $assignedBusinessUnitOptions = [];
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $assignedBusinessUnitOptions[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        $assignedCompanyIds = [];
        if (isset($submittedFormData[SspAssetForm::FIELD_ASSIGNED_COMPANIES])) {
            $assignedCompanyIds = array_map('intval', $submittedFormData[SspAssetForm::FIELD_ASSIGNED_COMPANIES]);
        }

        $companyCollectionTransfer = $this->companyFacade->getCompanyCollection(
            (new CompanyCriteriaFilterTransfer())->setCompanyIds($assignedCompanyIds),
        );

        $assignedCompanyOptions = [];
        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $assignedCompanyOptions[$companyTransfer->getNameOrFail()] = $companyTransfer->getIdCompanyOrFail();
        }

        $expandedFormOptions = [
            SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => $assignedBusinessUnitOptions,
            SspAssetForm::OPTION_BUSINESS_UNIT_OWNER => $businessUnitOwnerId ? [array_flip($assignedBusinessUnitOptions)[$businessUnitOwnerId] => $businessUnitOwnerId] : [],
            SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS => $assignedCompanyOptions,
        ];

        return array_merge($options, $expandedFormOptions);
    }

    public function getAssetImageUrl(SspAssetTransfer $sspAssetTransfer): ?string
    {
        if (!$sspAssetTransfer->getImage()) {
            return null;
        }

        return Url::generate(static::ROUTE_NAME_ASSET_VIEW_IMAGE, ['ssp-asset-reference' => $sspAssetTransfer->getReferenceOrFail()])->build();
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer> $sspAssetAssignmentTransfers
     *
     * @return array<string, int>
     */
    protected function getAssignedBusinessUnits(ArrayObject $sspAssetAssignmentTransfers): array
    {
        $assignedBusinessUnits = [];
        foreach ($sspAssetAssignmentTransfers as $sspAssetAssignmentTransfer) {
            $companyBusinessUnitTransfer = $sspAssetAssignmentTransfer->getCompanyBusinessUnit();
            if ($companyBusinessUnitTransfer) {
                $assignedBusinessUnits[sprintf(
                    '%s (ID: %s)',
                    $companyBusinessUnitTransfer->getNameOrFail(),
                    $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
                )] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            }
        }

        return $assignedBusinessUnits;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer> $sspAssetAssignmentTransfers
     *
     * @return array<string, int>
     */
    protected function getAssignedCompanies(ArrayObject $sspAssetAssignmentTransfers): array
    {
        $assignedCompanies = [];
        foreach ($sspAssetAssignmentTransfers as $sspAssetAssignmentTransfer) {
            $companyBusinessUnitTransfer = $sspAssetAssignmentTransfer->getCompanyBusinessUnit();
            if ($companyBusinessUnitTransfer) {
                $companyTransfer = $companyBusinessUnitTransfer->getCompanyOrFail();

                $assignedCompanies[sprintf(
                    '%s (ID: %s)',
                    $companyTransfer->getNameOrFail(),
                    $companyTransfer->getIdCompanyOrFail(),
                )] = $companyTransfer->getIdCompanyOrFail();
            }
        }

        return $assignedCompanies;
    }
}
