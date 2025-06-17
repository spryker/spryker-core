<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class DownloadAssetImageController extends AbstractController
{
    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewImageAction(Request $request): Response
    {
        $sspAssetReference = $request->get('ssp-asset-reference');

        $sspAssetCollectionTransfer = $this->getClient()->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())
                ->setInclude(
                    (new SspAssetIncludeTransfer())
                        ->setWithImageFile(true),
                )
                ->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())
                        ->addReference($sspAssetReference),
                ),
        );

        /** @var \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer */
        $sspAssetTransfer = $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();

        return $this->createResponse($sspAssetTransfer->getImageOrFail());
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
        /** @var \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer */
        $fileInfoTransfer = $fileTransfer->getFileInfo()->getIterator()->current();

        $fileStream = $this->getFactory()
            ->getFileManagerService()
            ->readStream($fileInfoTransfer->getStorageFileNameOrFail(), $fileInfoTransfer->getStorageNameOrFail());

        $chunkSize = $this->getFactory()->getConfig()->getSspAssetImageDownloadChunkSize();

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

        $fileName = basename($fileInfoTransfer->getStorageFileNameOrFail());

        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $fileName);

        $response->headers->set(static::HEADER_CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $fileInfoTransfer->getTypeOrFail());

        return $response;
    }
}
