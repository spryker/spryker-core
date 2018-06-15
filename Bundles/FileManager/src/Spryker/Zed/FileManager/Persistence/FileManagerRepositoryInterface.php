<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\MimeTypeTransfer;

interface FileManagerRepositoryInterface
{
    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function getFileByIdFile(int $idFile);

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function getFileByIdFileInfo(int $idFileInfo);

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function getLatestFileInfoByIdFile(int $idFile);

    /**
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function getMimeType(int $idMimeType);

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function getMimeTypeByIdMimeTypeAndName(MimeTypeTransfer $mimeTypeTransfer);

    /**
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function getAllowedMimeTypes();
}
