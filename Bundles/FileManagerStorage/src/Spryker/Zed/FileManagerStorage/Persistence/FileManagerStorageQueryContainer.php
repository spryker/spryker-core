<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStoragePersistenceFactory getFactory()
 */
class FileManagerStorageQueryContainer extends AbstractQueryContainer
{
    /**
     * @api
     *
     * @param array $fileIds
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFilesByIds($fileIds)
    {
        $query = $this->getFactory()
            ->createFileQuery();
        $query->filterByIdFile($fileIds, Criteria::IN);
        $query->useSpyFileInfoQuery()
            ->orderByVersion(Criteria::DESC)
        ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @param int $fkFile
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryLatestFileInfoByFkFile($fkFile)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByFkFile($fkFile)
            ->orderByVersion(Criteria::DESC);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function queryFileManagerStorage()
    {
        return $this->getFactory()->createFileManagerStorageQuery();
    }
}
