<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileVersion implements FileVersionInterface
{
    /**
     * @var int
     */
    protected const INITIAL_VERSION_NUMBER = 1;

    /**
     * @var string
     */
    protected const VERSION_FORMAT = 'v.%d';

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     */
    public function __construct(FileManagerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int|null $idFile
     *
     * @return int
     */
    public function getNextVersionNumber(?int $idFile = null)
    {
        if ($idFile === null) {
            return static::INITIAL_VERSION_NUMBER;
        }

        $fileInfoTransfer = $this->repository->getLatestFileInfoByIdFile($idFile);

        if ($fileInfoTransfer === null) {
            return static::INITIAL_VERSION_NUMBER;
        }

        return $fileInfoTransfer->getVersion() + 1;
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function getNextVersionName(int $versionNumber)
    {
        return sprintf(static::VERSION_FORMAT, $versionNumber);
    }
}
