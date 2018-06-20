<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileVersion implements FileVersionInterface
{
    protected const INITIAL_VERSION_NUMBER = 1;
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
        $fileInfo = $this->repository->getLatestFileInfoByIdFile($idFile);

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
    public function getNextVersionName(int $versionNumber)
    {
        return sprintf(static::VERSION_FORMAT, $versionNumber);
    }
}
