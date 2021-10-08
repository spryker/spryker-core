<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystemExtension\Dependency\Plugin;

use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;

interface FileSystemReaderPluginInterface
{
    /**
     * Specification:
     * - Gets resource mime type
     * - Returns resource mime type, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Gets resource timestamp
     * - Returns resource timestamp, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return int|null
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Gets resource size
     * - Returns resource size, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return int
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Checks if resource has private access rights
     * - Returns true if resource has private access rights
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Checks if resource exists
     * - Returns true if resource exist, false otherwise
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Reads file
     * - Returns file content, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Gets list contents under a path
     * - Returns array of FileSystemResourceTransfer objects located under given path, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return array<\Generated\Shared\Transfer\FileSystemResourceTransfer>
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer);
}
