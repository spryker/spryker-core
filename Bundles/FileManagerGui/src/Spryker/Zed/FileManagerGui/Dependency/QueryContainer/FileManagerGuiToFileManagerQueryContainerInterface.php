<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\QueryContainer;

interface FileManagerGuiToFileManagerQueryContainerInterface
{
    /**
     * @param int $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById(int $idFile);

    /**
     * @param int $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileById(int $idFile);

    /**
     * @param int $idFileDirectory
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function queryFileDirectoryId(int $idFileDirectory);

    /**
     * @param int|null $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile(int $idFile = null);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo(int $idFileInfo);

    /**
     * @api
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFiles();
}
