<?php


namespace Spryker\Zed\FileManager\Persistence;


use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method FileManagerPersistenceFactory getFactory()
 */
class FileManagerQueryContainer extends AbstractQueryContainer
{

    /**
     * @param int $id
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryFileWithFileInfoById(int $id)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($id);
        $query->leftJoinWithSpyFileInfo();

        return $query;
    }

    /**
     * @param int $id
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryFileById(int $id)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($id);

        return $query;
    }

    /**
     * @param int $fileId
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryLatestFileInfoByFkFile(int $fileId = null)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->orderByVersion(Criteria::DESC)
            ->filterByFkFile($fileId);

        return $query;
    }

    /**
     * @param int $fileInfoId
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryFileInfo(int $fileInfoId)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByIdFileInfo($fileInfoId);

        return $query;
    }

}