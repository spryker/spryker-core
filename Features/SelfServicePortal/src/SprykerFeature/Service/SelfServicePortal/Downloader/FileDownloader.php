<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Service\SelfServicePortal\Downloader;

use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Transliterator;

class FileDownloader implements FileDownloaderInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const TRANSLITERATOR_RULE = 'Any-Latin;Latin-ASCII;';

    /**
     * @param \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    public function __construct(protected FileManagerServiceInterface $fileManagerService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int $chunkSize
     * @param string $disposition
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function createFileDownloadResponse(
        FileTransfer $fileTransfer,
        int $chunkSize,
        string $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT
    ): StreamedResponse {
        /** @var \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer */
        $fileInfoTransfer = $fileTransfer->getFileInfo()->getIterator()->current();

        try {
            $fileStream = $this->fileManagerService->readStream(
                $fileInfoTransfer->getStorageFileNameOrFail(),
                $fileInfoTransfer->getStorageNameOrFail(),
            );
        } catch (FileSystemStreamException $e) {
            $this->getLogger()->error(
                'Error reading file stream',
                [
                    'exception' => $e,
                    'fileInfo' => $fileInfoTransfer->toArray(),
                ],
            );

            return $this->createErrorResponse();
        }

        $response = new StreamedResponse(function () use ($fileStream, $chunkSize): void {
            while (!feof($fileStream)) {
                $chunk = fread($fileStream, max(1, $chunkSize));
                if ($chunk === false) {
                    break;
                }
                echo $chunk;
                flush();
            }
            fclose($fileStream);
        });

        $fileName = $fileTransfer->getFileNameOrFail();
        $transliteratedFileName = $this->transliterateFileName($fileName);

        $dispositionHeader = $response->headers->makeDisposition($disposition, $fileName, $transliteratedFileName);

        $response->headers->set(static::CONTENT_DISPOSITION, $dispositionHeader);
        $response->headers->set(static::CONTENT_TYPE, $fileInfoTransfer->getTypeOrFail());

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function createErrorResponse(): StreamedResponse
    {
        return new StreamedResponse(function (): void {
            echo 'File not available';
        }, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function transliterateFileName(string $fileName): string
    {
        $transliterator = Transliterator::create(static::TRANSLITERATOR_RULE);

        return $transliterator ? (string)$transliterator->transliterate($fileName) : $fileName;
    }
}
