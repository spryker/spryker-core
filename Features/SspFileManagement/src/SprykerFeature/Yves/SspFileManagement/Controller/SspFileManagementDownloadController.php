<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Controller;

use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\DownloadFilesPermissionPlugin;
use SprykerFeature\Yves\SspFileManagement\Exception\SspFileManagementAccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementFactory getFactory()
 * @method \SprykerFeature\Client\SspFileManagement\SspFileManagementClientInterface getClient()
 */
class SspFileManagementDownloadController extends SspFileManagementAbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const PARAM_ID_FILE = 'id-file';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \SprykerFeature\Yves\SspFileManagement\Exception\SspFileManagementAccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request): StreamedResponse
    {
        if (!$request->query->has(static::PARAM_ID_FILE)) {
            throw new BadRequestHttpException();
        }

        if (!$this->can(DownloadFilesPermissionPlugin::KEY)) {
            throw new SspFileManagementAccessDeniedHttpException();
        }

        $fileAttachmentFileCriteriaTransfer = $this->createFileAttachmentFileCriteriaTransfer($request);
        $fileAttachmentFileCollectionTransfer = $this->getClient()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        if (!$fileAttachmentFileCollectionTransfer->getFiles()->count()) {
            throw new NotFoundHttpException();
        }

        return $this->createDownloadResponse($fileAttachmentFileCollectionTransfer->getFiles()->offsetGet(0));
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function createDownloadResponse(FileTransfer $fileTransfer): StreamedResponse
    {
        /** @var \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer */
        $fileInfoTransfer = $fileTransfer->getFileInfo()->offsetGet(0);

        $fileStream = $this->getFactory()
            ->getFileManagerService()
            ->readStream($fileInfoTransfer->getStorageFileNameOrFail(), $fileInfoTransfer->getStorageNameOrFail());

        $chunkSize = $this->getFactory()->getConfig()->getDownloadChunkSize();

        $response = new StreamedResponse(function () use ($fileStream, $chunkSize): void {
            while (!feof($fileStream)) {
                echo fread($fileStream, $chunkSize);
                flush();
            }
            fclose($fileStream);
        });

        $fileName = basename($fileInfoTransfer->getStorageFileNameOrFail());
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set(static::HEADER_CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::HEADER_CONTENT_TYPE, $fileInfoTransfer->getTypeOrFail());

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer
     */
    protected function createFileAttachmentFileCriteriaTransfer(Request $request): FileAttachmentFileCriteriaTransfer
    {
        $uuid = (string)$request->query->get(static::PARAM_ID_FILE);

        return (new FileAttachmentFileCriteriaTransfer())
            ->setCompanyUser($this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser())
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer())
            ->setFileAttachmentFileConditions(
                (new FileAttachmentFileConditionsTransfer())->setUuids([$uuid]),
            );
    }
}
