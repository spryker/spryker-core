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
     * {@inheritdoc}
     */
    public function save(FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileManagerSaveRequestTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function readLatestFileVersion($idFile)
    {
        return $this->getFactory()->createFileReader()->readLatestByFileId($idFile);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function delete($idFile)
    {
        return $this->getFactory()->createFileRemover()->delete($idFile);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function deleteFileInfo($idFileInfo)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function rollback($idFile, $idFileInfo)
    {
        $this->getFactory()->createFileRollback()->rollback($idFile, $idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function read($idFileInfo)
    {
        return $this->getFactory()->createFileReader()->read($idFileInfo);
    }
}
