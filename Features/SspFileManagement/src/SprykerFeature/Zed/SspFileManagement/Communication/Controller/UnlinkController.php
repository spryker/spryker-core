<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspFileManagement\Communication\Exception\InvalidEntityTypeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface getFileManagerFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class UnlinkController extends AbstractController
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_ENTITY_TYPE = 'Invalid entity type.';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_FILE_ATTACHMENT_UNLINKED = 'File attachment successfully unlinked.';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_FILE_ATTACHMENTS_UNLINKED = 'File attachments successfully unlinked.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerFeature\Zed\SspFileManagement\Communication\Exception\InvalidEntityTypeException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idFile = $request->query->getInt(static::REQUEST_PARAM_ID_FILE);
        $redirectUrl = Url::generate(static::URL_SSP_FILE_MANAGEMENT_VIEW, [static::REQUEST_PARAM_ID_FILE => $idFile]);

        $form = $this->getFactory()->createUnlinkFileForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CSRF_TOKEN_INVALID);

            return $this->redirectResponse($redirectUrl);
        }

        $entityType = (string)$request->query->get(static::REQUEST_PARAM_ENTITY_TYPE);
        $entityId = $request->query->getInt(static::REQUEST_PARAM_ENTITY_ID);

        $fileAttachmentCollectionDeleteCriteriaTransfer = (new FileAttachmentCollectionDeleteCriteriaTransfer())->addIdFile($idFile);

        $entityTypeMapping = $this->getEntityTypeMapping($entityType, $fileAttachmentCollectionDeleteCriteriaTransfer, $entityId);
        if ($entityType && !$entityTypeMapping) {
            throw new InvalidEntityTypeException(static::ERROR_MESSAGE_INVALID_ENTITY_TYPE);
        }

        $fileAttachmentCollectionRequestTransfer = new FileAttachmentCollectionRequestTransfer();
        $fileAttachmentCollectionRequestTransfer->setIdFile($idFile);
        $fileAttachmentCollectionRequestTransfer->addFileAttachmentToRemove(
            (new FileAttachmentTransfer())->setFile((new FileTransfer())->setIdFile($idFile))->setEntityName($entityType)->setEntityId($entityId),
        );

        $fileAttachmentCollectionResponseTransfer = $this->getFacade()->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);

        if ($fileAttachmentCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->addErrorMessagesFromFileAttachmentCollectionResponse($fileAttachmentCollectionResponseTransfer);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage($entityType ? static::SUCCESS_MESSAGE_FILE_ATTACHMENT_UNLINKED : static::SUCCESS_MESSAGE_FILE_ATTACHMENTS_UNLINKED);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer $fileAttachmentCollectionResponseTransfer
     *
     * @return void
     */
    protected function addErrorMessagesFromFileAttachmentCollectionResponse(
        FileAttachmentCollectionResponseTransfer $fileAttachmentCollectionResponseTransfer
    ): void {
        foreach ($fileAttachmentCollectionResponseTransfer->getErrors() as $errorTransfer) {
            /** @var string $message */
            $message = $errorTransfer->getMessage();

            $this->addErrorMessage($message);
        }
    }

    /**
     * @param string $entityType
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     * @param int $entityId
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer|false
     */
    protected function getEntityTypeMapping(
        string $entityType,
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer,
        int $entityId
    ): false|FileAttachmentCollectionDeleteCriteriaTransfer {
        return match ($entityType) {
            SspFileManagementConfig::ENTITY_TYPE_COMPANY => $fileAttachmentCollectionDeleteCriteriaTransfer->addIdCompany($entityId),
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => $fileAttachmentCollectionDeleteCriteriaTransfer->addIdCompanyBusinessUnit($entityId),
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => $fileAttachmentCollectionDeleteCriteriaTransfer->addIdCompanyUser($entityId),
            SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET => $fileAttachmentCollectionDeleteCriteriaTransfer->addIdSspAsset($entityId),
            default => false,
        };
    }
}
