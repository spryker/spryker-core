<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerBusinessFactory getFactory()
 */
class FileManagerFacade extends AbstractFacade implements FileManagerFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($saveRequestTransfer);
    }

    /**
     * @api
     *
     * @param int $fileId
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function readLatestFileVersion($fileId)
    {
        return $this->getFactory()->createFileReader()->read($fileId);
    }

    /**
     * @api
     *
     * @param int $fileId
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return bool
     */
    public function delete($fileId)
    {
        return $this->getFactory()->createFileRemover()->delete($fileId);
    }

    /**
     * @api
     *
     * @param int $fileInfoId
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return bool
     */
    public function deleteFileInfo($fileInfoId)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($fileInfoId);
    }

    /**
     * @api
     *
     * @param int $fileId
     * @param int $fileInfoId
     *
     * @return void
     */
    public function rollback($fileId, $fileInfoId)
    {
        $this->getFactory()->createFileRollback()->rollback($fileId, $fileInfoId);
    }
}
