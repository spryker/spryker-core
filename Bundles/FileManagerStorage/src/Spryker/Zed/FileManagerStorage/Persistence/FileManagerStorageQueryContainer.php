<?php

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method FileManagerStoragePersistenceFactory getFactory()
 */
class FileManagerStorageQueryContainer extends AbstractQueryContainer
{

    /**
     * @param $fileIds
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryFilesByIds($fileIds)
    {
        $query = $this->getFactory()
            ->createFileQuery();
        $query->filterByIdFile($fileIds, Criteria::IN);

        return $query;
    }

    /**
     * @param $fkFile
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryLatestFileInfoByFkFile($fkFile)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByFkFile($fkFile)
            ->orderByVersion(Criteria::DESC);

        return $query;
    }

    /**
     * @return SpyFileStorageQuery
     */
    public function queryFileManagerStorage()
    {
        return $this->getFactory()->createFileManagerStorageQuery();
    }

}