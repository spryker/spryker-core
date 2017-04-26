<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;

class FileSystemAdapter implements FileSystemAdapterInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Model\FileSystemReaderInterface
     */
    protected $fileSystemReader;

    /**
     * @var \Spryker\Service\FileSystem\Model\FileSystemWriterInterface
     */
    protected $fileSystemWriter;

    /**
     * @var \Spryker\Service\FileSystem\Model\FileSystemStreamInterface
     */
    protected $fileSystemStream;

    /**
     * @param \Spryker\Service\FileSystem\Model\FileSystemReaderInterface $fileSystemReader
     * @param \Spryker\Service\FileSystem\Model\FileSystemWriterInterface $fileSystemWriter
     * @param \Spryker\Service\FileSystem\Model\FileSystemStreamInterface $fileSystemStream
     */
    public function __construct(
        FileSystemReaderInterface $fileSystemReader,
        FileSystemWriterInterface $fileSystemWriter,
        FileSystemStreamInterface $fileSystemStream
    ) {
        $this->fileSystemReader = $fileSystemReader;
        $this->fileSystemWriter = $fileSystemWriter;
        $this->fileSystemStream = $fileSystemStream;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer|null
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getMetadata($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getMimetype($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->isPrivate($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        return $this->fileSystemWriter->markAsPrivate($fileSystemVisibilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        return $this->fileSystemWriter->markAsPublic($fileSystemVisibilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getTimestamp($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int|false
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getSize($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return bool
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        return $this->fileSystemWriter->createDirectory($fileSystemCreateDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @return bool
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer)
    {
        return $this->fileSystemWriter->deleteDirectory($fileSystemDeleteDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return bool
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        return $this->fileSystemWriter->copy($fileSystemCopyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return bool
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        return $this->fileSystemWriter->delete($fileSystemDeleteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->has($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->fileSystemWriter->put($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->read($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return bool
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        return $this->fileSystemWriter->rename($fileSystemRenameTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->fileSystemWriter->update($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->fileSystemWriter->write($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStream->putStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed|false
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer)
    {
        return $this->fileSystemStream->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStream->updateStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStream->writeStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer)
    {
        return $this->fileSystemReader->listContents($fileSystemListTransfer);
    }

}
