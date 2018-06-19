<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileDirectoryReader implements FileDirectoryReaderInterface
{
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
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function getFileDirectory(int $idFileDirectory)
    {
        return $this->repository->getFileDirectory($idFileDirectory);
    }
}
