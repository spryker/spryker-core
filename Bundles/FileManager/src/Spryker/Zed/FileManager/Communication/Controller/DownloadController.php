<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Communication\Controller;

use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\FileManager\FileManagerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method \Spryker\Zed\FileManager\Communication\FileManagerCommunicationFactory getFactory()
 */
class DownloadController extends AbstractController
{
    const CONTENT_DISPOSITION = 'Content-Disposition';
    const CONTENT_TYPE = 'Content-Type';
    const MESSAGE_FILE_UNAVAILABLE = 'File is unavailable';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idFileInfo = $request->get(FileManagerConstants::URL_PARAM_ID_FILE_INFO);

        try {
            $file = $this->getFactory()
                ->getFileManagerFacade()
                ->readFile($idFileInfo);
        } catch (FileSystemReadException $e) {
            $this->addErrorMessage(static::MESSAGE_FILE_UNAVAILABLE);
            $redirectUrl = Url::generate('/file-manager-gui')->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->createResponse($file);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerReadResponseTransfer $fileManagerReadResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileManagerReadResponseTransfer $fileManagerReadResponseTransfer)
    {
        $response = new Response($fileManagerReadResponseTransfer->getContent());
        $fileName = $fileManagerReadResponseTransfer->getFile()->getFileName();
        $contentType = $fileManagerReadResponseTransfer->getFileInfo()->getType();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
