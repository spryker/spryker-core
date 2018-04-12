<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

interface FileFinderInterface
{
    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    public function getFile($idFile);

    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function getLatestFileInfoByFkFile($idFile);

    /**
     * @param int $idFileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function getFileInfo($idFileInfo);

    /**
     * @param int $idFileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    public function getFileDirectory($idFileDirectory);
}
