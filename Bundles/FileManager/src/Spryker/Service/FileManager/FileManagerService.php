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
     * @param string $filePath
     *
     * @return string
     */
    public function save(string $filePath)
    {
        return $this->getFactory()->createFileManagerAdapter()->save($filePath);
    }

    /**
     * @param string $contentId
     *
     * @return mixed
     */
    public function read(string $contentId)
    {
        return $this->getFactory()->createFileManagerAdapter()->read($contentId);
    }

    /**
     * @param string $contentId
     *
     * @return bool
     */
    public function delete(string $contentId)
    {
        return $this->getFactory()->createFileManagerAdapter()->delete($contentId);
    }
}
