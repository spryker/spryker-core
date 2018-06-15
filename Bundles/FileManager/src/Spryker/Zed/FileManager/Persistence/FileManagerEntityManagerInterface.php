<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;

interface FileManagerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function saveFile(FileTransfer $fileTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    public function saveFileInfo(FileInfoTransfer $fileInfoTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $attributesTransfer
     *
     * @return \Generated\Shared\Transfer\FileLocalizedAttributesTransfer
     */
    public function saveLocalizedFileAttribute(FileLocalizedAttributesTransfer $attributesTransfer);

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer);

    /**
     * @param int $idMimeType
     *
     * @return void
     */
    public function deleteMimeType(int $idMimeType);
}
