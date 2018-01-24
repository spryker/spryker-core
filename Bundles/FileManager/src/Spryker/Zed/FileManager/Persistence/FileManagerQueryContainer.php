<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerQueryContainer extends AbstractQueryContainer implements FileManagerQueryContainerInterface
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
    public function queryFileWithFileInfoById($idFile)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($idFile);
        $query->leftJoinWithSpyFileInfo();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileById($idFile)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($idFile);

        return $query;
    }

    /**
     * @api
     *
     * @param int|null $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile($idFile = null)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->orderByVersion(Criteria::DESC)
            ->filterByFkFile($idFile);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo($idFileInfo)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByIdFileInfo($idFileInfo);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFiles()
    {
        $query = $this->getFactory()->createFileQuery();

        return $query;
    }
}
