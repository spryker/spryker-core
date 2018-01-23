<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerBusinessFactory getFactory()
 */
interface FileManagerFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer);

    /**
     * @api
     *
     * @param int $fileId
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readLatestFileVersion($fileId);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function delete($idFile);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo);

    /**
     * @api
     *
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollback($idFile, $idFileInfo);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($idFileInfo);
}
