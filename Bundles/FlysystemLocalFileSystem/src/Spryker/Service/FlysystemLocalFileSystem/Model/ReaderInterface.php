<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model;

interface ReaderInterface
{

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer|null
     */
    public function getMetadata($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getMimeType($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getVisibility($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getTimestamp($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getSize($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function read($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer[]
     */
    public function listContents($filesystemName, $directory = '', $recursive = false);

}
