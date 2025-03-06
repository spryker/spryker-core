<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\AttachFileForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class FileAttachController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_FILE_ATTACHMENT = 'fileAttachment';

    /**
     * @var string
     */
    protected const MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS = 'File attachments have been created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idFile = $request->get(static::REQUEST_PARAM_ID_FILE);
        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())->addIdFile($idFile);
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer);
        $fileAttachmentCollectionTransfer = $this->getFacade()
            ->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $formData = $this->getFormDataFromRequest($request);

        if ($formData === []) {
            $formData = $this->getFactory()
                ->createFileAttachmentMapper()
                ->mapFileAttachmentCollectionTransferToFormData($fileAttachmentCollectionTransfer);
        }

        $fileManagerDataTransfer = $this->getFactory()
            ->getFileManagerFacade()
            ->findFileByIdFile($idFile);

        $dataProvider = $this->getFactory()->createFileAttachFormDataProvider();
        $form = $this->getFactory()
            ->createAttachFileForm(
                $dataProvider->getData($formData),
                $dataProvider->getOptions($fileAttachmentCollectionTransfer),
            );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processForm($form->getData(), $fileManagerDataTransfer, $idFile);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'urlSspFileManagementList' => Url::generate(static::URL_SSP_FILE_MANAGEMENT_LIST)->build(),
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
        $formData = [];

        if ($fileAttachmentFormData) {
            $formData[AttachFileForm::FIELD_COMPANY_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_IDS] ?? [];
            $formData[AttachFileForm::FIELD_COMPANY_USER_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_USER_IDS] ?? [];
            $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS] = $fileAttachmentFormData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS] ?? [];
        }

        return $formData;
    }

    /**
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     * @param int $idFile
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processForm(array $formData, FileManagerDataTransfer $fileManagerDataTransfer, int $idFile): RedirectResponse
    {
        $fileAttachmentCollectionTransfer = $this->getFactory()
            ->createFileAttachmentMapper()
            ->mapFormDataToFileAttachmentCollectionTransfer($formData, $fileManagerDataTransfer, $idFile);

        $fileAttachmentCollectionRequestTransfer = (new FileAttachmentCollectionRequestTransfer())
            ->setIdFile($idFile)
            ->setFileAttachments($fileAttachmentCollectionTransfer->getFileAttachments());

        $this->getFacade()->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);

        $this->addSuccessMessage(static::MESSAGE_FILE_ATTACHMENTS_CREATE_SUCCESS);
        $redirectUrl = Url::generate(static::URL_SSP_FILE_MANAGEMENT_LIST)->build();

        return $this->redirectResponse($redirectUrl);
    }
}
