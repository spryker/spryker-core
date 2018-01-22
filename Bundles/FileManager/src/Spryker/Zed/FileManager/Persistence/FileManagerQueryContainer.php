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
     * @param int $id
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById(int $id)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($id);
        $query->leftJoinWithSpyFileInfo();

        return $query;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileById(int $id)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($id);

        return $query;
    }

    /**
     * @api
     *
     * @param int|null $idFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile(int $idFile = null)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->orderByVersion(Criteria::DESC)
            ->filterByFkFile($idFile);

        return $query;
    }

    /**
     * @api
     *
     * @param int $fileInfoId
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo(int $fileInfoId)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByIdFileInfo($fileInfoId);

        return $query;
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFiles()
    {
        $query = $this->getFactory()->createFileQuery();

        return $query;
    }

}
