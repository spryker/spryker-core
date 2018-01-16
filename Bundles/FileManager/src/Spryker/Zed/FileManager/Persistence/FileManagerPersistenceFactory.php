<?php


namespace Spryker\Zed\FileManager\Persistence;


use Orm\Zed\Cms\Persistence\SpyFileInfoLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyFileInfoQuery;
use Orm\Zed\Cms\Persistence\SpyFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class FileManagerPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyFileQuery
     */
    public function createFileQuery()
    {
        return SpyFileQuery::create();
    }

    /**
     * @return SpyFileInfoQuery
     */
    public function createFielInfoQuery()
    {
        return SpyFileInfoQuery::create();
    }

    /**
     * @return SpyFileInfoLocalizedAttributesQuery
     */
    public function createFileInfoLocalizedAttributesQuery()
    {
        return SpyFileInfoLocalizedAttributesQuery::create();
    }

}