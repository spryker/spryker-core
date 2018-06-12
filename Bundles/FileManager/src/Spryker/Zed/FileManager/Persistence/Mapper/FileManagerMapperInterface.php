<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyMimeType;

interface FileManagerMapperInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function mapFileInfoEntityToTransfer(SpyFileInfo $fileInfo);

    /**
     * @param \Orm\Zed\FileManager\Persistence\Base\SpyMimeType $mimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function mapMimeTypeEntityToTransfer(SpyMimeType $mimeType);
}
