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
            'unattachedSspAssetTable' => $this->getFactory()->createUnattachedSspAssetAttachmentTable($idFile)->render(),
            'attachedSspAssetTable' => $this->getFactory()->createAttachedSspAssetAttachmentTable($idFile)->render(),
            'unattachedBusinessUnitTable' => $this->getFactory()->createUnattachedBusinessUnitAttachmentTable($idFile)->render(),
            'attachedBusinessUnitTable' => $this->getFactory()->createAttachedBusinessUnitAttachmentTable($idFile)->render(),
            'unattachedCompanyUserTable' => $this->getFactory()->createUnattachedCompanyUserAttachmentTable($idFile)->render(),
            'attachedCompanyUserTable' => $this->getFactory()->createAttachedCompanyUserAttachmentTable($idFile)->render(),
            'unattachedCompanyTable' => $this->getFactory()->createUnattachedCompanyAttachmentTable($idFile)->render(),
            'attachedCompanyTable' => $this->getFactory()->createAttachedCompanyAttachmentTable($idFile)->render(),
            'unattachedSspModelTable' => $this->getFactory()->createUnattachedModelAttachmentTable($idFile)->render(),
            'attachedSspModelTable' => $this->getFactory()->createAttachedSspModelAttachmentTable($idFile)->render(),
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()->createUnattachedSspAssetAttachmentTable($idFile)->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()->createAttachedSspAssetAttachmentTable($idFile)->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()->createUnattachedBusinessUnitAttachmentTable($idFile)->fetchBusinessUnitsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()->createAttachedBusinessUnitAttachmentTable($idFile)->fetchBusinessUnitsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()->createUnattachedCompanyUserAttachmentTable($idFile)->fetchCompanyUsersByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()->createAttachedCompanyUserAttachmentTable($idFile)->fetchCompanyUsersByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()
                    ->createUnattachedCompanyAttachmentTable($idFile)
                    ->fetchCompaniesByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()
                    ->createAttachedCompanyAttachmentTable($idFile)
                    ->fetchCompaniesByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()
                    ->createUnattachedModelAttachmentTable($idFile)
                    ->fetchModelsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()
                    ->createAttachedSspModelAttachmentTable($idFile)
                    ->fetchModelsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
            ],
        ]);
    }

    public function unattachedSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnattachedSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function attachedSspAssetTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAttachedSspAssetAttachmentTable($idFile)->fetchData());
    }

    public function unattachedBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnattachedBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function attachedBusinessUnitTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAttachedBusinessUnitAttachmentTable($idFile)->fetchData());
    }

    public function unattachedCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnattachedCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function attachedCompanyUserTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAttachedCompanyUserAttachmentTable($idFile)->fetchData());
    }

    public function unattachedCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnattachedCompanyAttachmentTable($idFile)->fetchData());
    }

    public function attachedCompanyTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAttachedCompanyAttachmentTable($idFile)->fetchData());
    }

    public function unattachedSspModelTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createUnattachedModelAttachmentTable($idFile)->fetchData());
    }

    public function attachedSspModelTableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        return $this->jsonResponse($this->getFactory()->createAttachedSspModelAttachmentTable($idFile)->fetchData());
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
