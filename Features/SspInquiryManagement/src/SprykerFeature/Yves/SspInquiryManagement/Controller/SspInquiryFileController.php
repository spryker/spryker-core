<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Controller;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Yves\SspInquiryManagement\Plugin\Router\SspInquiryRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Transliterator;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementFactory getFactory()
 * @method \SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface getClient()
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryFileController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const MESSAGE_FILE_UNAVAILABLE = 'ssp_inquiry.file.unavailable';

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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request): Response|RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            throw new Exception('Customer not found');
        }

         $sspInquiryFileUuid = $request->get('uuid');
         $sspInquiryReference = $request->get('ssp-inquiry-reference');

         $sspInquiryTransfer = $this->getFactory()
            ->createSspInquiryReader()
            ->getSspInquiry($sspInquiryReference, $customerTransfer->getCompanyUserTransferOrFail());

        if (!$sspInquiryTransfer) {
            throw new NotFoundHttpException(sprintf(
                "Ssp Inquiry with provided Reference %s doesn't exist",
                $sspInquiryReference,
            ));
        }

        if (!$this->getFactory()->createSspInquiryCustomerPermissionChecker()->canViewSspInquiry($sspInquiryTransfer, $customerTransfer->getCompanyUserTransferOrFail())) {
            throw new AccessDeniedHttpException('ssp_inquiry.access.denied');
        }

        $idFile = null;
        foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
            if ($fileTransfer->getUuid() === $sspInquiryFileUuid) {
                $idFile = $fileTransfer->getIdFile();

                break;
            }
        }

        if ($idFile === null) {
            throw new NotFoundHttpException(sprintf(
                "File with provided UUID %s doesn't exist",
                $sspInquiryFileUuid,
            ));
        }

        try {
            $fileManagerDataTransfer = $this->getClient()->downloadSspInquiryFile((new SspInquiryFileDownloadRequestTransfer())->setFileId($idFile));
        } catch (FileSystemReadException $e) {
            $this->addErrorMessage(static::MESSAGE_FILE_UNAVAILABLE);

            return $this->redirectResponseInternal(SspInquiryRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_DETAILS, [
                'reference' => $sspInquiryTransfer->getReference(),
            ]);
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
