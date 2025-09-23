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
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;
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
    protected const ATTACHMENT_SCOPE_MODEL = 'model';

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

        return $this->viewResponse([
            'fileName' => $fileAttachmentTransfer->getFileOrFail()->getFileNameOrFail(),
            'fileAttachForm' => $attachFileForm->createView(),
            'attachmentScopeTabsViewTransfer' => $this->getFactory()->createAttachmentScopeTabs()->createView(),
            'assetAttachmentTabsViewTransfer' => $this->getFactory()->createAssetAttachmentTabs()->createView(),
            'urlPathListFile' => Url::generate(static::URL_PATH_LIST_FILE)->build(),
            'urlDownloadExample' => Url::generate(static::URL_PATH_DOWNLOAD_EXAMPLE)->build(),
            'urlDownloadBusinessUnitExample' => Url::generate(static::URL_PATH_DOWNLOAD_BUSINESS_UNIT_EXAMPLE)->build(),
            'urlDownloadCompanyUserExample' => Url::generate(static::URL_PATH_DOWNLOAD_COMPANY_USER_EXAMPLE)->build(),
            'urlDownloadCompanyExample' => Url::generate(static::URL_PATH_DOWNLOAD_COMPANY_EXAMPLE)->build(),
            'urlDownloadModelExample' => Url::generate(static::URL_PATH_DOWNLOAD_MODEL_EXAMPLE)->build(),
            'tabsViewTransfer' => $this->getFactory()->createFileAttachmentTabs()->createView(),
            'attachedAssetsTabsViewTransfer' => $this->getFactory()->createAttachedAssetsTabs()->createView(),
            'businessUnitTabsViewTransfer' => $this->getFactory()->createBusinessUnitAttachmentTabs()->createView(),
            'attachedBusinessUnitsTabsViewTransfer' => $this->getFactory()->createAttachedBusinessUnitsTabs()->createView(),
            'companyUserTabsViewTransfer' => $this->getFactory()->createCompanyUserAttachmentTabs()->createView(),
            'attachedCompanyUsersTabsViewTransfer' => $this->getFactory()->createAttachedCompanyUsersTabs()->createView(),
            'companyTabsViewTransfer' => $this->getFactory()->createCompanyAttachmentTabs()->createView(),
            'attachedCompaniesTabsViewTransfer' => $this->getFactory()->createAttachedCompaniesTabs()->createView(),
            'modelAttachmentTabsViewTransfer' => $this->getFactory()->createModelAttachmentTabs()->createView(),
            'attachedModelsTabsViewTransfer' => $this->getFactory()->createAttachedModelsTabs()->createView(),
            'idFile' => $idFile,
            'availableAssetTable' => $this->getFactory()->createUnassignedSspAssetAttachmentTable($idFile)->render(),
            'assignedAssetTable' => $this->getFactory()->createAssignedSspAssetAttachmentTable($idFile)->render(),
            'availableBusinessUnitTable' => $this->getFactory()->createUnassignedBusinessUnitAttachmentTable($idFile)->render(),
            'assignedBusinessUnitTable' => $this->getFactory()->createAssignedBusinessUnitAttachmentTable($idFile)->render(),
            'availableCompanyUserTable' => $this->getFactory()->createUnassignedCompanyUserAttachmentTable($idFile)->render(),
            'assignedCompanyUserTable' => $this->getFactory()->createAssignedCompanyUserAttachmentTable($idFile)->render(),
            'availableCompanyTable' => $this->getFactory()->createUnassignedCompanyAttachmentTable($idFile)->render(),
            'assignedCompanyTable' => $this->getFactory()->createAssignedCompanyAttachmentTable($idFile)->render(),
            'availableModelTable' => $this->getFactory()->createUnassignedModelAttachmentTable($idFile)->render(),
            'assignedModelTable' => $this->getFactory()->createAssignedModelAttachmentTable($idFile)->render(),
        ]);
    }

    protected function handleFormSubmission(
        Request $request,
        FormInterface $attachFileForm,
        int $idFile,
        FileAttachmentTransfer $fileAttachmentTransfer
    ): RedirectResponse {
        if (!$attachFileForm->isValid()) {
            return $this->redirectToIndex($idFile);
        }

        $strategyResolver = $this->getFactory()->createAttachmentScopeStrategyResolver();
        $selectedScope = $attachFileForm->get(static::FORM_FIELD_ATTACHMENT_SCOPE)->getData() ?? static::ATTACHMENT_SCOPE_ASSET;

        if ($strategyResolver->canProcessScope($selectedScope, $attachFileForm)) {
            $formData = $strategyResolver->getFormDataForScope($selectedScope, $request);

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
            static::ATTACHMENT_SCOPE_MODEL => $attachmentProcessor->processModelForm($formData, $idFile, $fileAttachmentTransfer),
            default => $this->redirectToIndex($idFile)
        };

        if ($result instanceof RedirectResponse) {
            $this->addSuccessMessage(static::MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS);
        }

        return $result;
    }

    public function getAssetAttachmentsFromCsvAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->jsonResponse([
                'error' => 'No file uploaded',
            ], 400);
        }

        $parsedCsvData = $this->getFactory()
            ->createRelationCsvReader()
            ->readRelations($uploadedFile);

        return $this->jsonResponse([
            'data' => [
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED => $this->getFactory()->createUnassignedSspAssetAttachmentTable($idFile)->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED => $this->getFactory()->createAssignedSspAssetAttachmentTable($idFile)->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED]),
            ],
        ]);
    }

    public function getBusinessUnitAttachmentsFromCsvAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->jsonResponse([
                'error' => 'No file uploaded',
            ], 400);
        }

        $parsedCsvData = $this->getFactory()
            ->createRelationCsvReader()
            ->readRelations($uploadedFile);

        return $this->jsonResponse([
            'data' => [
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED => $this->getFactory()->createUnassignedBusinessUnitAttachmentTable($idFile)->fetchBusinessUnitsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED => $this->getFactory()->createAssignedBusinessUnitAttachmentTable($idFile)->fetchBusinessUnitsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED]),
            ],
        ]);
    }

    public function getCompanyUserAttachmentsFromCsvAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->jsonResponse([
                'error' => 'No file uploaded',
            ], 400);
        }

        $parsedCsvData = $this->getFactory()
            ->createRelationCsvReader()
            ->readRelations($uploadedFile);

        return $this->jsonResponse([
            'data' => [
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED => $this->getFactory()->createUnassignedCompanyUserAttachmentTable($idFile)->fetchCompanyUsersByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED => $this->getFactory()->createAssignedCompanyUserAttachmentTable($idFile)->fetchCompanyUsersByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED]),
            ],
        ]);
    }

    public function getCompanyAttachmentsFromCsvAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->jsonResponse([
                'error' => 'No file uploaded',
            ], 400);
        }

        $parsedCsvData = $this->getFactory()
            ->createRelationCsvReader()
            ->readRelations($uploadedFile);

        return $this->jsonResponse([
            'data' => [
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED => $this->getFactory()
                    ->createUnassignedCompanyAttachmentTable($idFile)
                    ->fetchCompaniesByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED => $this->getFactory()
                    ->createAssignedCompanyAttachmentTable($idFile)
                    ->fetchCompaniesByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED]),
            ],
        ]);
    }

    public function getModelAttachmentsFromCsvAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->jsonResponse([
                'error' => 'No file uploaded',
            ], 400);
        }

        $parsedCsvData = $this->getFactory()
            ->createRelationCsvReader()
            ->readRelations($uploadedFile);

        return $this->jsonResponse([
            'data' => [
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED => $this->getFactory()
                    ->createUnassignedModelAttachmentTable($idFile)
                    ->fetchModelsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED => $this->getFactory()
                    ->createAssignedModelAttachmentTable($idFile)
                    ->fetchModelsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED]),
            ],
        ]);
    }

    public function availableSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnassignedSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function assignedSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function availableBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnassignedBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function assignedBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function availableCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnassignedCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function assignedCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function availableCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnassignedCompanyAttachmentTable($idFile)->fetchData());
    }

    public function assignedCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedCompanyAttachmentTable($idFile)->fetchData());
    }

    public function availableModelTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnassignedModelAttachmentTable($idFile)->fetchData());
    }

    public function assignedModelTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAssignedModelAttachmentTable($idFile)->fetchData());
    }

    protected function getFileAttachmentTransfer(int $idFile): FileAttachmentTransfer
    {
        $fileAttachmentCollectionTransfer = $this->getFacade()
            ->getFileAttachmentCollection((new FileAttachmentCriteriaTransfer())
                ->setFileAttachmentConditions((new FileAttachmentConditionsTransfer())->addIdFile($idFile))
                ->setUser($this->getFactory()->getUserFacade()->getCurrentUser())
                ->setWithSspAssetRelation(true)
                ->setWithBusinessUnitRelation(true)
                ->setWithCompanyUserRelation(true)
                ->setWithSspModelRelation(true));

        $fileAttachmentTransfer = $fileAttachmentCollectionTransfer->getFileAttachments()->getIterator()->current();

        if (!$fileAttachmentTransfer) {
            throw new Exception(sprintf('File attachment with id %d not found.', $idFile));
        }

        return $fileAttachmentTransfer;
    }

    protected function redirectToIndex(int $idFile): RedirectResponse
    {
        return $this->redirectResponse(
            Url::generate(static::URL_PATH_ATTACH_FILE, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
        );
    }
}
