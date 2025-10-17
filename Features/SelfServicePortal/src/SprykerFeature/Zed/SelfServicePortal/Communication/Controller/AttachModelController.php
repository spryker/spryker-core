<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AttachModelController extends AbstractController
{
    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_SSP_MODEL = 'id-ssp-model';

    /**
     * @var string
     */
    protected const URL_PATH_VIEW_MODEL = '/self-service-portal/view-model';

    /**
     * @var string
     */
    protected const URL_PATH_DOWNLOAD_ASSET_EXAMPLE = '/self-service-portal/attach-model/download-asset-example';

    /**
     * @var string
     */
    protected const URL_PATH_DOWNLOAD_PRODUCT_LIST_EXAMPLE = '/self-service-portal/attach-model/download-product-list-example';

    /**
     * @var string
     */
    protected const MESSAGE_MODEL_ATTACHMENTS_CREATE_SUCCESS = 'Model attachments have been created successfully.';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_MODEL_ATTACHMENT = 'modelAttachment';

    /**
     * @var string
     */
    protected const REQUEST_FIELD_MODEL_ATTACHMENT = 'modelAttachment';

    /**
     * @var string
     */
    protected const TAB_PRODUCT_LISTS = 'tab-content-attached-product-lists';

    /**
     * @var string
     */
    protected const EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT = "Asset to be attached,Asset to be detached\nAST--1,AST--4\nAST--2,AST--5\nAST--3,AST--6";

    /**
     * @var string
     */
    protected const EXAMPLE_PRODUCT_LIST_CSV_ATTACHMENT_FILE_CONTENT = "Product list to be attached,Product list to be detached\n1,4\n2,5\n3,6";

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));
        $sspModelTransfer = $this->getSspModelTransfer($idSspModel);

        $attachModelForm = $this->getFactory()->createAttachModelForm($sspModelTransfer);
        $attachModelForm->handleRequest($request);

        if ($request->getMethod() === Request::METHOD_POST) {
            return $this->handleFormSubmission($request, $attachModelForm, $idSspModel, $sspModelTransfer);
        }

        return $this->viewResponse([
            'modelName' => $sspModelTransfer->getNameOrFail(),
            'attachModelForm' => $attachModelForm->createView(),
            'attachmentScopeTabsViewTransfer' => $this->getFactory()->createModelRelationScopeTabs()->createView(),
            'assetAttachmentTabsViewTransfer' => $this->getFactory()->createModelAssetRelationTabs()->createView(),
            'productListAttachmentTabsViewTransfer' => $this->getFactory()->createModelProductListRelationTabs()->createView(),
            'attachedAssetsTabsViewTransfer' => $this->getFactory()->createAttachedModelAssetsTabs()->createView(),
            'attachedProductListsTabsViewTransfer' => $this->getFactory()->createAttachedModelProductListsTabs()->createView(),
            'urlPathViewModel' => Url::generate(static::URL_PATH_VIEW_MODEL, [static::REQUEST_PARAM_ID_SSP_MODEL => $idSspModel])->build(),
            'urlDownloadAssetExample' => Url::generate(static::URL_PATH_DOWNLOAD_ASSET_EXAMPLE)->build(),
            'urlDownloadProductListExample' => Url::generate(static::URL_PATH_DOWNLOAD_PRODUCT_LIST_EXAMPLE)->build(),
            'idSspModel' => $idSspModel,
            'unattachedSspAssetTable' => $this->getFactory()->createUnattachedModelAssetAttachmentTable($idSspModel)->render(),
            'attachedSspAssetTable' => $this->getFactory()->createAttachedSspModelAssetAttachmentTable($idSspModel)->render(),
            'unattachedProductListTable' => $this->getFactory()->createUnattachedModelProductListAttachmentTable($idSspModel)->render(),
            'attachedProductListTable' => $this->getFactory()->createAttachedSspModelProductListAttachmentTable($idSspModel)->render(),
        ]);
    }

    protected function handleFormSubmission(
        Request $request,
        FormInterface $attachModelForm,
        int $idSspModel,
        SspModelTransfer $sspModelTransfer
    ): RedirectResponse {
        if (!$attachModelForm->isValid()) {
            return $this->redirectToIndex($idSspModel);
        }

        /** @var \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer */
        $sspModelCollectionRequestTransfer = $attachModelForm->getData();

        $this->getFacade()->updateSspModelCollection($sspModelCollectionRequestTransfer);
        $this->addSuccessMessage(static::MESSAGE_MODEL_ATTACHMENTS_CREATE_SUCCESS);

        $hash = null;
        if ($this->hasProductListAttachments($sspModelCollectionRequestTransfer)) {
            $hash = static::TAB_PRODUCT_LISTS;
        }

        return $this->redirectToIndex($idSspModel, $hash);
    }

    public function unattachedSspAssetTableAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));

        return $this->jsonResponse($this->getFactory()->createUnattachedModelAssetAttachmentTable($idSspModel)->fetchData());
    }

    public function attachedSspAssetTableAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));

        return $this->jsonResponse($this->getFactory()->createAttachedSspModelAssetAttachmentTable($idSspModel)->fetchData());
    }

    public function unattachedProductListTableAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));

        return $this->jsonResponse($this->getFactory()->createUnattachedModelProductListAttachmentTable($idSspModel)->fetchData());
    }

    public function attachedProductListTableAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));

        return $this->jsonResponse($this->getFactory()->createAttachedSspModelProductListAttachmentTable($idSspModel)->fetchData());
    }

    public function downloadAssetExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT, 'example-model-asset-assignments.csv');
    }

    public function downloadProductListExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_PRODUCT_LIST_CSV_ATTACHMENT_FILE_CONTENT, 'example-model-product-list-assignments.csv');
    }

    public function getRelationsFromCsvAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()->createUnattachedModelAssetAttachmentTable($idSspModel)
                    ->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()->createAttachedSspModelAssetAttachmentTable($idSspModel)
                    ->fetchAssetsByReferences($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
            ],
        ]);
    }

    public function getProductListRelationsFromCsvAction(Request $request): JsonResponse
    {
        $idSspModel = $this->castId($request->get(static::REQUEST_PARAM_ID_SSP_MODEL));
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
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $this->getFactory()->createUnattachedModelProductListAttachmentTable($idSspModel)->fetchProductListsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED]),
                RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $this->getFactory()->createAttachedSspModelProductListAttachmentTable($idSspModel)->fetchProductListsByIds($parsedCsvData[RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED]),
            ],
        ]);
    }

    protected function createCsvDownloadResponse(string $content, string $filename): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($content): void {
            echo $content;
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    protected function getSspModelTransfer(int $idSspModel): SspModelTransfer
    {
        $sspModelCollectionTransfer = $this->getFacade()
            ->getSspModelCollection((new SspModelCriteriaTransfer())
                ->setSspModelConditions((new SspModelConditionsTransfer())->addIdSspModel($idSspModel))
                ->setWithSspAssets(true)
                ->setWithProductLists(true));

        $sspModelTransfer = $sspModelCollectionTransfer->getSspModels()->getIterator()->current();

        if (!$sspModelTransfer) {
            throw new NotFoundHttpException(sprintf('SSP model with id %d not found.', $idSspModel));
        }

        return $sspModelTransfer;
    }

    protected function redirectToIndex(int $idSspModel, ?string $hash = null): RedirectResponse
    {
        $url = Url::generate(static::URL_PATH_VIEW_MODEL, [static::REQUEST_PARAM_ID_SSP_MODEL => $idSspModel])->build();

        if ($hash) {
            $url .= '#' . $hash;
        }

        return $this->redirectResponse(
            $url,
        );
    }

    protected function hasProductListAttachments(SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): bool
    {
        if (count($sspModelCollectionRequestTransfer->getProductListsToBeAttached())) {
            return true;
        }

        if (count($sspModelCollectionRequestTransfer->getProductListsToBeUnattached())) {
            return true;
        }

        return false;
    }
}
