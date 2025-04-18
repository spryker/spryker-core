<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\FileManager\FileManagerServiceFactory getFactory()
 */
class FileManagerService extends AbstractService implements FileManagerServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function read(string $fileName)
    {
        return $this->getFactory()
            ->createFileReader()
            ->read($fileName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $fileName
     * @param string|null $storageName
     *
     * @return mixed
     */
    public function readStream(string $fileName, ?string $storageName = null)
    {
        return $this->getFactory()
            ->createFileReader()
            ->readStream($fileName, $storageName);
    }
}
