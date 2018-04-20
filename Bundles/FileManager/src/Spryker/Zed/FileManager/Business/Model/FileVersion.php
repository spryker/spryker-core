<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

class FileVersion implements FileVersionInterface
{
    const INITIAL_VERSION_NUMBER = 1;
    const VERSION_FORMAT = 'v.%d';

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @param \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface $fileLoader
     */
    public function __construct(FileLoaderInterface $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * @param int|null $idFile
     *
     * @return int
     */
    public function getNextVersionNumber($idFile = null)
    {
        if ($idFile === null) {
            return static::INITIAL_VERSION_NUMBER;
        }
        $fileInfo = $this->fileLoader->getLatestFileInfoByFkFile($idFile);

        if ($fileInfo === null) {
            return static::INITIAL_VERSION_NUMBER;
        }

        return $fileInfo->getVersion() + 1;
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function getNextVersionName($versionNumber)
    {
        return sprintf(static::VERSION_FORMAT, $versionNumber);
    }
}
