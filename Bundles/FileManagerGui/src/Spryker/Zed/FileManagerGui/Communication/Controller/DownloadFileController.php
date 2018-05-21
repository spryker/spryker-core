<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DownloadFileController extends AbstractController
{
    const URL_PARAM_ID_FILE_INFO = 'id-file-info';
    const CONTENT_DISPOSITION = 'Content-Disposition';
    const CONTENT_TYPE = 'Content-Type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idFileInfo = $request->get(static::URL_PARAM_ID_FILE_INFO);

        $fileManagerDataTransfer = $this->getFactory()
            ->getFileManagerFacade()
            ->readFile($idFileInfo);

        if ($fileManagerDataTransfer->getContent() === null) {
            throw new NotFoundHttpException();
        }

        return $this->createResponse($fileManagerDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $response = new Response($fileManagerDataTransfer->getContent());
        $fileName = $fileManagerDataTransfer->getFile()->getFileName();
        $contentType = $fileManagerDataTransfer->getFileInfo()->getType();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
