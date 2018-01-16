<?php


namespace Spryker\Zed\FileManager\Persistence;


use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method FileManagerPersistenceFactory getFactory()
 */
class FileManagerQueryContainer extends AbstractQueryContainer
{

    /**
     * @param int $id
     *
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

}