<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Service\SelfServicePortal\Downloader;

use Generated\Shared\Transfer\FileTransfer;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileDownloaderInterface
{
    public function createFileDownloadResponse(
        FileTransfer $fileTransfer,
        int $chunkSize,
        string $disposition = 'attachment'
    ): StreamedResponse;
}
