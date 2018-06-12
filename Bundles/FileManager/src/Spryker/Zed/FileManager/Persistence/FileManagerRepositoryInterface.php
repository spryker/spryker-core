<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

interface FileManagerRepositoryInterface
{
    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function getFileInfoById(int $idFileInfo);

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
    public function findMimeType(int $idMimeType);

    /**
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function getAllowedMimeTypes();
}
