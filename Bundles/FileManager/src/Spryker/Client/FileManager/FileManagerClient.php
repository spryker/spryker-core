<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager;

use Generated\Shared\Transfer\ReadFileTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\FileManager\FileManagerFactory getFactory()
 */
class FileManagerClient extends AbstractClient implements FileManagerClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReadFileTransfer $readFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFile(ReadFileTransfer $readFileTransfer)
    {
        return $this->getFactory()
            ->createFileReader()
            ->readFileVersion(
                $readFileTransfer->getIdFile()
            );
    }
}
