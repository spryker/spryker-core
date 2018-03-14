<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\FileManager\FileManagerConstants;

/**
 * @method \Spryker\Service\FileManager\FileManagerServiceFactory getFactory()
 */
class FileManagerService extends AbstractService implements FileManagerServiceInterface
{
    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getPublicUrl(string $fileName)
    {
        return sprintf('/download/%s', $fileName);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getZedUrl(string $fileName)
    {
        return sprintf('/file-manager/download?%s=%s', FileManagerConstants::URL_PARAM_ID_FILE_INFO, $fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $fileName)
    {
        return $this->getFactory()
            ->createFileReader()
            ->read($fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function readStream(string $fileName)
    {
        return $this->getFactory()
            ->createFileReader()
            ->readStream($fileName);
    }
}
