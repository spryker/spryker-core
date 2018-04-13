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
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById($idFile);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileById($idFile);

    /**
     * @api
     *
     * @param int $idFileDirectory
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function queryFileDirectoryById($idFileDirectory);

    /**
     * @api
     *
     * @param int|null $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile($idFile = null);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
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
