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
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\SspAssetAttachmentForm;
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
    protected const REQUEST_PARAM_FILE_ATTACHMENT = 'fileAttachment';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT = 'sspAssetFileAttachment';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SSP_ASSET_IDS = 'sspAssetIds';

    /**
     * @var string
     */
    protected const MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS = 'File attachments have been created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $fileAttachmentCollectionTransfer = $this->getFacade()
            ->getFileAttachmentCollection((new FileAttachmentCriteriaTransfer())
                ->setFileAttachmentConditions(
                    (new FileAttachmentConditionsTransfer())
                        ->addIdFile($idFile),
                )
                ->setUser($this->getFactory()->getUserFacade()->getCurrentUser())
                ->setWithSspAssetRelation(true)
                ->setWithCompanyRelation(true)
                ->setWithBusinessUnitRelation(true)
                ->setWithCompanyUserRelation(true));

        $fileAttachmentTransfer = $fileAttachmentCollectionTransfer->getFileAttachments()->getIterator()->current();

        if (!$fileAttachmentTransfer) {
            throw new Exception(
                sprintf('File attachment with id %d not found.', $idFile),
            );
        }

        $formData = $this->getFormDataFromRequest($request);
        if (!$formData) {
            $formData = $this->getFactory()
                ->createFileAttachmentMapper()
                ->mapFileAttachmentCollectionTransferToFormData($fileAttachmentTransfer);
        }

        $formDataProvider = $this->getFactory()->createFileAttachFormDataProvider();
        $attachFileForm = $this->getFactory()
            ->createAttachFileForm(
                $formDataProvider->getData($formData),
                $formDataProvider->getOptions($fileAttachmentTransfer),
            );

        $assetFormDataProvider = $this->getFactory()->createAssetAttachmentFormDataProvider();
        $sspAssetAttachmentForm = $this->getFactory()
            ->createSspAssetAttachmentForm(
                $assetFormDataProvider->getData($formData),
                $assetFormDataProvider->getOptions($idFile, $request->get(static::REQUEST_PARAM_SSP_ASSET_IDS, [])),
            );

        $tabsViewTransfer = $this->getFactory()->createFileAttachmentTabs()->createView();

        $attachFileForm->handleRequest($request);
        $sspAssetAttachmentForm->handleRequest($request);

        if ($attachFileForm->isSubmitted() && $attachFileForm->isValid()) {
            return $this->processForm($attachFileForm->getData(), $idFile, $fileAttachmentTransfer);
        }

        if ($sspAssetAttachmentForm->isSubmitted() && $sspAssetAttachmentForm->isValid()) {
            return $this->processForm($sspAssetAttachmentForm->getData(), $idFile, $fileAttachmentTransfer);
        }

        return $this->viewResponse([
            'fileAttachForm' => $attachFileForm->createView(),
            'sspAssetFileAttachForm' => $sspAssetAttachmentForm->createView(),
            'urlPathListFile' => Url::generate(static::URL_PATH_LIST_FILE)->build(),
            'tabsViewTransfer' => $tabsViewTransfer,
            'idFile' => $idFile,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, array<int>>
     */
    protected function getFormDataFromRequest(Request $request): array
    {
        if ($request->getMethod() !== Request::METHOD_POST) {
            return [];
        }

        $fileAttachmentFormData = $request->get(static::REQUEST_PARAM_FILE_ATTACHMENT);
        $sspAssetFileAttachmentFormData = $request->get(static::REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT);
        $formData = [];

        if ($fileAttachmentFormData) {
            $formData[AttachFileForm::FIELD_COMPANY_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_IDS] ?? [];
            $formData[AttachFileForm::FIELD_COMPANY_USER_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_USER_IDS] ?? [];
            $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS] ?? [];
        }

        if ($sspAssetFileAttachmentFormData) {
            $formData[SspAssetAttachmentForm::FIELD_ASSET_IDS] = $sspAssetFileAttachmentFormData[SspAssetAttachmentForm::FIELD_ASSET_IDS] ?? [];
        }

        return $formData;
    }

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse
    {
        $businessFormData = [
            AttachFileForm::FIELD_COMPANY_IDS => $formData[AttachFileForm::FIELD_COMPANY_IDS] ?? null,
            AttachFileForm::FIELD_COMPANY_USER_IDS => $formData[AttachFileForm::FIELD_COMPANY_USER_IDS] ?? null,
            AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS] ?? null,
            SspAssetAttachmentForm::FIELD_ASSET_IDS => $formData[SspAssetAttachmentForm::FIELD_ASSET_IDS] ?? null,
        ];

        $fileAttachmentCollectionRequestTransfer = $this->getFactory()
            ->createFileAttachmentMapper()
            ->mapFormDataToFileAttachmentCollectionTransfer($fileAttachmentTransfer, $businessFormData, $idFile);

        if ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToAdd()->count()) {
            $this->getFacade()->createFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);
        }

        if ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToDelete()->count()) {
            $this->getFacade()->deleteFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);
        }

        $this->addSuccessMessage(static::MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS);

        return $this->redirectResponse(
            Url::generate(static::URL_PATH_VIEW_FILE, [static::REQUEST_PARAM_ID_FILE => $idFile])->build(),
        );
    }
}
