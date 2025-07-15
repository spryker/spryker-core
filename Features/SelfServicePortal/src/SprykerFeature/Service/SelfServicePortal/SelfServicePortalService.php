<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Service\SelfServicePortal;

use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\Kernel\AbstractService;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Service\SelfServicePortal\SelfServicePortalServiceFactory getFactory()
 */
class SelfServicePortalService extends AbstractService implements SelfServicePortalServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
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
        return $this->getFactory()
            ->createFileDownloader()
            ->createFileDownloadResponse($fileTransfer, $chunkSize, $disposition);
    }
}
