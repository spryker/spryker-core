<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Service\SelfServicePortal;

use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerFeature\Service\SelfServicePortal\Downloader\FileDownloader;
use SprykerFeature\Service\SelfServicePortal\Downloader\FileDownloaderInterface;

/**
 * @method \Spryker\Service\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalServiceFactory extends AbstractServiceFactory
{
    public function createFileDownloader(): FileDownloaderInterface
    {
        return new FileDownloader(
            $this->getFileManagerService(),
        );
    }

    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_FILE_MANAGER);
    }
}
