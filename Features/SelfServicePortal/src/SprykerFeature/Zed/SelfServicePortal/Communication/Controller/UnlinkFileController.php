<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class UnlinkFileController extends FileAbstractController
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $redirectUrl = Url::generate(static::URL_PATH_VIEW_FILE, [static::REQUEST_PARAM_ID_FILE => $request->query->getInt(static::REQUEST_PARAM_ID_FILE)]);

        $form = $this->getFactory()->createUnlinkFileForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CSRF_TOKEN_INVALID);

            return $this->redirectResponse($redirectUrl);
        }

        $fileAttachmentCollectionResponseTransfer = $this->getFacade()->deleteFileAttachmentCollection(
            $this->createDeleteCriteriaTransfer($request),
        );

        if ($fileAttachmentCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->addErrorMessagesFromFileAttachmentCollectionResponse($fileAttachmentCollectionResponseTransfer);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage($request->query->get(static::REQUEST_PARAM_ENTITY_TYPE) ? static::SUCCESS_MESSAGE_FILE_ATTACHMENT_UNLINKED : static::SUCCESS_MESSAGE_FILE_ATTACHMENTS_UNLINKED);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer
     */
    protected function createDeleteCriteriaTransfer(Request $request): FileAttachmentCollectionRequestTransfer
    {
        $entityId = $this->castId($request->query->getInt(static::REQUEST_PARAM_ENTITY_ID));

        $idFile = $this->castId($request->query->getInt(static::REQUEST_PARAM_ID_FILE));

        $fileAttachmentTransfer = (new FileAttachmentTransfer())->setFile((new FileTransfer())->setIdFile($idFile));

        $fileAttachmentTransfer = match ($request->query->get(static::REQUEST_PARAM_ENTITY_TYPE)) {
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY => $fileAttachmentTransfer->setCompanyCollection((new CompanyCollectionTransfer())->addCompany((new CompanyTransfer())->setIdCompany($entityId))),
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => $fileAttachmentTransfer->setBusinessUnitCollection((new CompanyBusinessUnitCollectionTransfer())->addCompanyBusinessUnit((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($entityId))),
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER => $fileAttachmentTransfer->setCompanyUserCollection((new CompanyUserCollectionTransfer())->addCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($entityId))),
            SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET => $fileAttachmentTransfer->setSspAssetCollection((new SspAssetCollectionTransfer())->addSspAsset((new SspAssetTransfer())->setIdSspAsset($entityId))),
            default => throw new Exception(static::ERROR_MESSAGE_INVALID_ENTITY_TYPE),
        };

        return (new FileAttachmentCollectionRequestTransfer())
            ->addFileAttachmentToDelete($fileAttachmentTransfer);
    }
}
