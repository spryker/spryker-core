<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AttachFileController extends FileAbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS = 'File attachments have been created successfully.';

    /**
     * @var string
     */
    protected const ATTACHMENT_SCOPE_ASSET = 'asset';

    /**
     * @var string
     */
    protected const ATTACHMENT_SCOPE_BUSINESS_UNIT = 'business-unit';

    /**
     * @var string
     */
    protected const ATTACHMENT_SCOPE_COMPANY_USER = 'company-user';

    /**
     * @var string
     */
    protected const ATTACHMENT_SCOPE_COMPANY = 'company';

    /**
     * @var string
     */
    protected const FORM_FIELD_ATTACHMENT_SCOPE = 'attachmentScope';

    /**
     * @var string
     */
    protected const REQUEST_FIELD_FILE_ATTACHMENT = 'fileAttachment';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $fileAttachmentTransfer = $this->getFileAttachmentTransfer($idFile);

        $formDataProcessor = $this->getFactory()->createFormDataProcessor();
        $formData = $formDataProcessor->getFormDataFromRequest($request);

        if (!$formData) {
            $formData = $this->getFactory()
                ->createFileAttachmentMapper()
                ->mapFileAttachmentCollectionTransferToFormData($fileAttachmentTransfer);
        }

        $attachFileForm = $this->getFactory()->createAttachFileForm($formData, $fileAttachmentTransfer, $idFile);

        $formDataProcessor->preprocessRequestData($request);
        $attachFileForm->handleRequest($request);

        if ($request->getMethod() === Request::METHOD_POST) {
            return $this->handleFormSubmission($request, $attachFileForm, $idFile, $fileAttachmentTransfer);
        }

        return $this->createViewResponse($fileAttachmentTransfer, $attachFileForm, $idFile);
    }

    protected function handleFormSubmission(
        Request $request,
        FormInterface $attachFileForm,
        int $idFile,
        FileAttachmentTransfer $fileAttachmentTransfer
    ): RedirectResponse {
        $strategyResolver = $this->getFactory()->createAttachmentScopeStrategyResolver();
        $selectedScope = $attachFileForm->get(static::FORM_FIELD_ATTACHMENT_SCOPE)->getData() ?? static::ATTACHMENT_SCOPE_ASSET;

        if ($strategyResolver->canProcessScope($selectedScope, $attachFileForm)) {
            $formData = $this->getFormDataWithCsvImports($selectedScope, $attachFileForm, $request);

            if ($formData !== null) {
                return $this->processAttachmentByScope($selectedScope, $formData, $idFile, $fileAttachmentTransfer);
            }
        }

        return $this->redirectToIndex($idFile);
    }

    /**
     * @param string $scopeType
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processAttachmentByScope(
        string $scopeType,
        array $formData,
        int $idFile,
        FileAttachmentTransfer $fileAttachmentTransfer
    ): RedirectResponse {
        $attachmentProcessor = $this->getFactory()->createAttachmentProcessor();

        $result = match ($scopeType) {
            static::ATTACHMENT_SCOPE_ASSET => $attachmentProcessor->processAssetForm($formData, $idFile, $fileAttachmentTransfer),
            static::ATTACHMENT_SCOPE_BUSINESS_UNIT => $attachmentProcessor->processBusinessUnitForm($formData, $idFile, $fileAttachmentTransfer),
            static::ATTACHMENT_SCOPE_COMPANY_USER => $attachmentProcessor->processCompanyUserForm($formData, $idFile, $fileAttachmentTransfer),
            static::ATTACHMENT_SCOPE_COMPANY => $attachmentProcessor->processCompanyForm($formData, $idFile, $fileAttachmentTransfer),
            default => $this->redirectToIndex($idFile)
        };

        if ($result instanceof RedirectResponse) {
            $this->addSuccessMessage(static::MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS);
        }

        return $result;
    }

    /**
     * @param string $selectedScope
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>|null
     */
    protected function getFormDataWithCsvImports(string $selectedScope, FormInterface $form, Request $request): ?array
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $csvProcessor = $this->getFactory()->createCsvImportProcessor();
        $csvData = $csvProcessor->processCsvImportsForScope($selectedScope, $form, $idFile);

        if ($csvData) {
            $existingFileAttachmentData = $request->request->all(static::REQUEST_FIELD_FILE_ATTACHMENT) ?: [];
            $mergedFileAttachmentData = array_merge($existingFileAttachmentData, $csvData);
            $request->request->set(static::REQUEST_FIELD_FILE_ATTACHMENT, $mergedFileAttachmentData);
        }

        $strategyResolver = $this->getFactory()->createAttachmentScopeStrategyResolver();

        return $strategyResolver->getFormDataForScope($selectedScope, $request);
    }

    public function availableSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAvailableSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function assignedSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function availableBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAvailableBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function assignedBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function availableCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAvailableCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function assignedCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function availableCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAvailableCompanyAttachmentTable($idFile)->fetchData());
    }

    public function assignedCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedCompanyAttachmentTable($idFile)->fetchData());
    }

    protected function getFileAttachmentTransfer(int $idFile): FileAttachmentTransfer
    {
        $fileAttachmentCollectionTransfer = $this->getFacade()
            ->getFileAttachmentCollection((new FileAttachmentCriteriaTransfer())
                ->setFileAttachmentConditions((new FileAttachmentConditionsTransfer())->addIdFile($idFile))
                ->setUser($this->getFactory()->getUserFacade()->getCurrentUser())
                ->setWithSspAssetRelation(true)
                ->setWithBusinessUnitRelation(true)
                ->setWithCompanyUserRelation(true));

        $fileAttachmentTransfer = $fileAttachmentCollectionTransfer->getFileAttachments()->getIterator()->current();

        if (!$fileAttachmentTransfer) {
            throw new Exception(sprintf('File attachment with id %d not found.', $idFile));
        }

        return $fileAttachmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param \Symfony\Component\Form\FormInterface $attachFileForm
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    protected function createViewResponse(FileAttachmentTransfer $fileAttachmentTransfer, FormInterface $attachFileForm, int $idFile): array
    {
        $attachmentScopeTabsViewTransfer = $this->getFactory()->createAttachmentScopeTabs()->createView();
        $assetAttachmentTabsViewTransfer = $this->getFactory()->createAssetAttachmentTabs()->createView();
        $tabsViewTransfer = $this->getFactory()->createFileAttachmentTabs()->createView();
        $attachedAssetsTabsViewTransfer = $this->getFactory()->createAttachedAssetsTabs()->createView();
        $businessUnitTabsViewTransfer = $this->getFactory()->createBusinessUnitAttachmentTabs()->createView();
        $attachedBusinessUnitsTabsViewTransfer = $this->getFactory()->createAttachedBusinessUnitsTabs()->createView();
        $companyUserTabsViewTransfer = $this->getFactory()->createCompanyUserAttachmentTabs()->createView();
        $attachedCompanyUsersTabsViewTransfer = $this->getFactory()->createAttachedCompanyUsersTabs()->createView();
        $companyTabsViewTransfer = $this->getFactory()->createCompanyAttachmentTabs()->createView();
        $attachedCompaniesTabsViewTransfer = $this->getFactory()->createAttachedCompaniesTabs()->createView();

        return $this->viewResponse([
            'fileName' => $fileAttachmentTransfer->getFileOrFail()->getFileNameOrFail(),
            'fileAttachForm' => $attachFileForm->createView(),
            'attachmentScopeTabsViewTransfer' => $attachmentScopeTabsViewTransfer,
            'assetAttachmentTabsViewTransfer' => $assetAttachmentTabsViewTransfer,
            'urlPathListFile' => Url::generate(static::URL_PATH_LIST_FILE)->build(),
            'urlImportAssetAssignments' => Url::generate(static::URL_PATH_IMPORT_ASSET_ASSIGNMENTS, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
            'urlDownloadExample' => Url::generate(static::URL_PATH_DOWNLOAD_EXAMPLE)->build(),
            'urlImportBusinessUnitAssignments' => Url::generate(static::URL_PATH_IMPORT_BUSINESS_UNIT_ASSIGNMENTS, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
            'urlDownloadBusinessUnitExample' => Url::generate(static::URL_PATH_DOWNLOAD_BUSINESS_UNIT_EXAMPLE)->build(),
            'urlImportCompanyUserAssignments' => Url::generate(static::URL_PATH_IMPORT_COMPANY_USER_ASSIGNMENTS, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
            'urlDownloadCompanyUserExample' => Url::generate(static::URL_PATH_DOWNLOAD_COMPANY_USER_EXAMPLE)->build(),
            'urlDownloadCompanyExample' => Url::generate(static::URL_PATH_DOWNLOAD_COMPANY_EXAMPLE)->build(),
            'urlImportCompanyAssignments' => Url::generate(static::URL_PATH_IMPORT_COMPANY_ASSIGNMENTS, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
            'tabsViewTransfer' => $tabsViewTransfer,
            'attachedAssetsTabsViewTransfer' => $attachedAssetsTabsViewTransfer,
            'businessUnitTabsViewTransfer' => $businessUnitTabsViewTransfer,
            'attachedBusinessUnitsTabsViewTransfer' => $attachedBusinessUnitsTabsViewTransfer,
            'companyUserTabsViewTransfer' => $companyUserTabsViewTransfer,
            'attachedCompanyUsersTabsViewTransfer' => $attachedCompanyUsersTabsViewTransfer,
            'companyTabsViewTransfer' => $companyTabsViewTransfer,
            'attachedCompaniesTabsViewTransfer' => $attachedCompaniesTabsViewTransfer,
            'idFile' => $idFile,
            'availableAssetTable' => $this->getFactory()->createAvailableSspAssetAttachmentTable($idFile)->render(),
            'assignedAssetTable' => $this->getFactory()->createAssignedSspAssetAttachmentTable($idFile)->render(),
            'availableBusinessUnitTable' => $this->getFactory()->createAvailableBusinessUnitAttachmentTable($idFile)->render(),
            'assignedBusinessUnitTable' => $this->getFactory()->createAssignedBusinessUnitAttachmentTable($idFile)->render(),
            'availableCompanyUserTable' => $this->getFactory()->createAvailableCompanyUserAttachmentTable($idFile)->render(),
            'assignedCompanyUserTable' => $this->getFactory()->createAssignedCompanyUserAttachmentTable($idFile)->render(),
            'availableCompanyTable' => $this->getFactory()->createAvailableCompanyAttachmentTable($idFile)->render(),
            'assignedCompanyTable' => $this->getFactory()->createAssignedCompanyAttachmentTable($idFile)->render(),
        ]);
    }

    protected function redirectToIndex(int $idFile): RedirectResponse
    {
        return $this->redirectResponse(
            Url::generate(static::URL_PATH_ATTACH_FILE, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
        );
    }
}
