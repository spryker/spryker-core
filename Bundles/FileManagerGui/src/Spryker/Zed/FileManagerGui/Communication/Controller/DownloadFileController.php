<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Transliterator;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DownloadFileController extends AbstractController
{
    /**
     * @var string
     */
    protected const URL_PARAM_ID_FILE_INFO = 'id-file-info';

    /**
     * @var string
     */
    protected const CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const MESSAGE_FILE_UNAVAILABLE = 'File was not found';

    /**
     * @var string
     */
    protected const TRANSLITERATOR_RULE = 'Any-Latin;Latin-ASCII;';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idFileInfo = $request->get(static::URL_PARAM_ID_FILE_INFO);

        try {
            $file = $this->getFactory()
                ->getFileManagerFacade()
                ->findFileByIdFileInfo($idFileInfo);
        } catch (FileSystemReadException $e) {
            $this->addErrorMessage(static::MESSAGE_FILE_UNAVAILABLE);
            $redirectUrl = Url::generate('/file-manager-gui')->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->createResponse($file);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $response = new Response($fileManagerDataTransfer->getContent());
        $fileName = $fileManagerDataTransfer->getFileOrFail()->getFileNameOrFail();
        $contentType = $fileManagerDataTransfer->getFileInfoOrFail()->getType() ?? '';
        $transliterator = Transliterator::create(static::TRANSLITERATOR_RULE);

        if ($transliterator === null) {
            return $response;
        }

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName,
            (string)$transliterator->transliterate($fileName),
        );

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
