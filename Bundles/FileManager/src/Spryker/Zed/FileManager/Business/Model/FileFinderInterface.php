<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

interface FileFinderInterface
{
    /**
     * @param int $fileId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFile
     */
    public function getFile(int $fileId);

    /**
     * @param int|null $fileId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    public function getLatestFileInfoByFkFile(int $fileId = null);

    /**
     * @param int $fileInfoId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    public function getFileInfo(int $fileInfoId);
}
