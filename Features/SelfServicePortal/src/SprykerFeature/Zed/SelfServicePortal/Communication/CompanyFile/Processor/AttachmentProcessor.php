<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\FormDataNormalizerInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AttachmentProcessor implements AttachmentProcessorInterface
{
    /**
     * @var string
     */
    protected const URL_PATH_VIEW_FILE = '/self-service-portal/view-file';

    public function __construct(
        protected SelfServicePortalFacadeInterface $facade,
        protected SelfServicePortalRepositoryInterface $repository,
        protected FormDataNormalizerInterface $formDataNormalizer,
        protected FileAttachmentMapperInterface $fileAttachmentMapper
    ) {
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processAssetForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $businessFormData = [
            AttachFileForm::FIELD_ASSET_IDS_TO_BE_ASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_ASSIGNED] ?? null),
            AttachFileForm::FIELD_ASSET_IDS_TO_BE_UNASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_UNASSIGNED] ?? null),
        ];

        return $this->processAttachment($businessFormData, $idFile, $fileAttachmentTransfer);
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processBusinessUnitForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $businessFormData = [
            AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED] ?? null),
            AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_UNASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_UNASSIGNED] ?? null),
        ];

        return $this->processAttachment($businessFormData, $idFile, $fileAttachmentTransfer);
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processCompanyUserForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $businessFormData = [
            AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_ASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_ASSIGNED] ?? null),
            AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_UNASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_UNASSIGNED] ?? null),
        ];

        return $this->processAttachment($businessFormData, $idFile, $fileAttachmentTransfer);
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processCompanyForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $companyIdsToAssign = $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_ASSIGNED] ?? null);
        $companyIdsToDeassign = $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_UNASSIGNED] ?? null);

        $businessUnitIdsToAssign = [];
        $businessUnitIdsToDeassign = [];

        if ($companyIdsToAssign) {
            foreach ($companyIdsToAssign as $companyId) {
                $businessUnitsForCompany = $this->repository->getBusinessUnitIdsForCompanies([(int)$companyId]);
                $businessUnitIdsToAssign = array_merge($businessUnitIdsToAssign, $businessUnitsForCompany);
            }
        }

        if ($companyIdsToDeassign) {
            foreach ($companyIdsToDeassign as $companyId) {
                $businessUnitsForCompany = $this->repository->getBusinessUnitIdsForCompanies([(int)$companyId]);
                $businessUnitIdsToDeassign = array_merge($businessUnitIdsToDeassign, $businessUnitsForCompany);
            }
        }

        $businessUnitFormData = [
            AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED => array_values(array_unique($businessUnitIdsToAssign)),
            AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_UNASSIGNED => array_values(array_unique($businessUnitIdsToDeassign)),
        ];

        return $this->processAttachment($businessUnitFormData, $idFile, $fileAttachmentTransfer);
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processModelForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $modelFormData = [
            AttachFileForm::FIELD_MODEL_IDS_TO_BE_ASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_MODEL_IDS_TO_BE_ASSIGNED] ?? null),
            AttachFileForm::FIELD_MODEL_IDS_TO_BE_UNASSIGNED => $this->formDataNormalizer->normalizeFormFieldArray($formData[AttachFileForm::FIELD_MODEL_IDS_TO_BE_UNASSIGNED] ?? null),
        ];

        return $this->processAttachment($modelFormData, $idFile, $fileAttachmentTransfer);
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processAttachment(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $fileAttachmentCollectionRequestTransfer = $this->fileAttachmentMapper
            ->mapFormDataToFileAttachmentCollectionTransfer($fileAttachmentTransfer, $formData, $idFile);

        if ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToAdd()->count()) {
            $this->facade->createFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);
        }

        if ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToDelete()->count()) {
            $this->facade->deleteFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);
        }

        return $this->createSuccessRedirectResponse($idFile);
    }

    protected function createSuccessRedirectResponse(int $idFile): RedirectResponse
    {
        return new RedirectResponse(
            Url::generate(static::URL_PATH_VIEW_FILE, [FileAbstractController::REQUEST_PARAM_ID_FILE => $idFile])->build(),
        );
    }
}
