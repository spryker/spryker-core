<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManagerStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\FileManagerStorage\FileStorageFactory getFactory()
 */
class FileManagerManagerStorageClient extends AbstractClient implements FileManagerStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageTransfer|null
     */
    public function findFileById($fileId, $localeName)
    {
        return $this->getFactory()
            ->createFileStorage()
            ->findFileById($fileId, $localeName);
    }
}
