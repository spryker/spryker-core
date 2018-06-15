<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes;
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
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    public function mapFileTransferToEntity(FileTransfer $fileTransfer, SpyFile $file);

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    public function mapFileInfoEntityToTransfer(SpyFileInfo $fileInfo, FileInfoTransfer $fileInfoTransfer);

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param \Generated\Shared\Transfer\SpyFileInfoEntityTransfer $fileInfoEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function mapFileInfoEntityToEntityTransfer(SpyFileInfo $fileInfo, SpyFileInfoEntityTransfer $fileInfoEntityTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function mapFileInfoTransferToEntity(FileInfoTransfer $fileInfoTransfer, SpyFileInfo $fileInfo);

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes $fileLocalizedAttributes
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes
     */
    public function mapFileLocalizedAttributesTransferToEntity(FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer, SpyFileLocalizedAttributes $fileLocalizedAttributes);

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    public function mapFileDirectoryTransferToEntity(FileDirectoryTransfer $fileDirectoryTransfer, SpyFileDirectory $fileDirectory);

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes
     */
    public function mapFileDirectoryLocalizedAttributesTransferToEntity(FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer, SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes);

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
