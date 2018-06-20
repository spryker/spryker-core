<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FileWidget\Dependency\Client;

class FileWidgetToFileBridge implements FileWidgetToFileInterface
{
    /**
     * @var \Spryker\Client\FileManagerStorage\FileManagerStorageClient
     */
    protected $fileManagerStorageClient;

    /**
     * @param \Spryker\Client\FileManagerStorage\FileManagerStorageClientInterface $fileManagerStorageClient
     */
    public function __construct($fileManagerStorageClient)
    {
        $this->fileManagerStorageClient = $fileManagerStorageClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findFileById($fileId, $localeName)
    {
        return $this->fileManagerStorageClient->findFileById($fileId, $localeName);
    }
}
