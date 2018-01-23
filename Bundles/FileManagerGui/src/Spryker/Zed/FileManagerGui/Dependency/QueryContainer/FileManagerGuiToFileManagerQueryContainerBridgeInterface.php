<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\QueryContainer;

interface FileManagerGuiToFileManagerQueryContainerBridgeInterface
{
    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById(int $id);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileById(int $id);

    /**
     * @param int|null $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile(int $idFile = null);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo(int $idFileInfo);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFiles();
}
