<?php

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Orm\Zed\Cms\Persistence\SpyFileInfoQuery;
use Orm\Zed\Cms\Persistence\SpyFileQuery;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class FileManagerStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyFileQuery
     */
    public function createFileQuery()
    {
        return new SpyFileQuery();
    }

    /**
     * @return SpyFileStorageQuery
     */
    public function createFileManagerStorageQuery()
    {
        return SpyFileStorageQuery::create();
    }

    /**
     * @return SpyFileInfoQuery
     */
    public function createFileInfoQuery()
    {
        return SpyFileInfoQuery::create();
    }

}