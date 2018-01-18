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
     * @return bool
     */
    public function delete(int $fileId);

    /**
     * @api
     *
     * @param int $fileInfoId
     *
     * @return bool
     */
    public function deleteFileInfo(int $fileInfoId);

    /**
     * @api
     *
     * @param int $fileId
     * @param int $fileInfoId
     *
     * @return void
     */
    public function rollback(int $fileId, int $fileInfoId);
}
