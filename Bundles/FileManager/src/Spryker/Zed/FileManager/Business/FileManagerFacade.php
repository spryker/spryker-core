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
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileManagerSaveRequestTransfer);
    }

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readLatestFileVersion($idFile)
    {
        return $this->getFactory()->createFileReader()->readLatestByFileId($idFile);
    }

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return bool
     */
    public function delete($idFile)
    {
        return $this->getFactory()->createFileRemover()->delete($idFile);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($idFileInfo);
    }

    /**
     * @api
     *
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollback($idFile, $idFileInfo)
    {
        $this->getFactory()->createFileRollback()->rollback($idFile, $idFileInfo);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($idFileInfo)
    {
        return $this->getFactory()->createFileReader()->read($idFileInfo);
    }
}
