<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
interface FileManagerQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById($idFile);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileById($idFile);

    /**
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function queryFileDirectoryById($idFileDirectory);

    /**
     * @api
     *
     * @param int|null $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByIdFile($idFile = null);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo($idFileInfo);

    /**
     * @api
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFiles();
}
