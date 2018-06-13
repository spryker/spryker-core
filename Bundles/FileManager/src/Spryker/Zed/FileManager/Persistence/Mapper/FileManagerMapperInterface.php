<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyMimeType;

interface FileManagerMapperInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function mapFileEntityToTransfer(SpyFile $file, FileTransfer $fileTransfer);

    /**
     * @param \Orm\Zed\FileManager\Persistence\Base\SpyMimeType $mimeType
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function mapMimeTypeEntityToTransfer(SpyMimeType $mimeType, MimeTypeTransfer $mimeTypeTransfer);

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyMimeType $mimeType
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyMimeType
     */
    public function mapMimeTypeTransferToEntity(MimeTypeTransfer $mimeTypeTransfer, SpyMimeType $mimeType);
}
