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
     * @return int
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
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
     * @return bool
     */
    public function delete(int $fileId)
    {
        return $this->getFactory()->createFileRemover()->delete($fileId);
    }

    /**
     * @api
     *
     * @param int $fileInfoId
     *
     * @return bool
     */
    public function deleteFileInfo(int $fileInfoId)
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
    public function rollback(int $fileId, int $fileInfoId)
    {
        $this->getFactory()->createFileRollback()->rollback($fileId, $fileInfoId);
    }
}
