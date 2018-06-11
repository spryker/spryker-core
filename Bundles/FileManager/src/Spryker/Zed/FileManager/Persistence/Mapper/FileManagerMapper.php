<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use Generated\Shared\Transfer\SpyFileEntityTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;

class FileManagerMapper implements FileManagerMapperInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function mapFileInfoEntityToTransfer(SpyFileInfo $fileInfo)
    {
        $fileInfoEntityTransfer = new SpyFileInfoEntityTransfer();
        $fileInfoEntityTransfer->fromArray($fileInfo->toArray(), true);
        $fileInfoEntityTransfer->setFile($this->mapFileEntityToTransfer($fileInfo->getFile()));

        return $fileInfoEntityTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return \Generated\Shared\Transfer\SpyFileEntityTransfer
     */
    protected function mapFileEntityToTransfer(SpyFile $file)
    {
        return (new SpyFileEntityTransfer())
            ->fromArray($file->toArray(), true);
    }
}
