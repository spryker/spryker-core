<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Transliterator;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class SspInquiryFileController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_FILE_UNAVAILABLE = 'File was not found';

    /**
     * @var string
     */
    protected const CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const TRANSLITERATOR_RULE = 'Any-Latin;Latin-ASCII;';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request): Response|RedirectResponse
    {
        $idFileInfo = $request->get('id-file');

        try {
            $fileManagerDataTransfer = $this->getFactory()
                ->getFileManagerFacade()
                ->findFileByIdFile($idFileInfo);
        } catch (FileSystemReadException $e) {
            $this->addErrorMessage(static::MESSAGE_FILE_UNAVAILABLE);
            $redirectUrl = Url::generate('/ssp-inquiry/list')->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->createResponse($fileManagerDataTransfer->getFileOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileTransfer $fileTransfer): Response
    {
        $transliterator = Transliterator::create(static::TRANSLITERATOR_RULE);

        if ($transliterator === null) {
            return new StreamedResponse();
        }

        /** @var \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer */
        $fileInfoTransfer = $fileTransfer->getFileInfo()->getIterator()->current();

        $fileStream = $this->getFactory()
            ->getFileManagerService()
            ->readStream($fileInfoTransfer->getStorageFileNameOrFail(), $fileInfoTransfer->getStorageNameOrFail());

        $chunkSize = $this->getFactory()->getConfig()->getDownloadChunkSize();

        if ($chunkSize <= 0) {
            throw new Exception('Chunk size is not valid');
        }

        $response = new StreamedResponse(function () use ($fileStream, $chunkSize): void {
            while (!feof($fileStream)) {
                $chunk = fread($fileStream, $chunkSize);
                if ($chunk === false) {
                    break;
                }
                echo $chunk;
                flush();
            }
            fclose($fileStream);
        });

        $fileName = $fileTransfer->getFileNameOrFail();

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName,
            (string)$transliterator->transliterate($fileName),
        );

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $fileInfoTransfer->getTypeOrFail());

        return $response;
    }
}
