<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Orm\Zed\Cms\Persistence\SpyFileInfoQuery;
use Orm\Zed\Cms\Persistence\SpyFileQuery;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageQueryContainer getQueryContainer()
 */
class FileManagerStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function createFileQuery()
    {
        return new SpyFileQuery();
    }

    /**
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function createFileManagerStorageQuery()
    {
        return SpyFileStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function createFileInfoQuery()
    {
        return SpyFileInfoQuery::create();
    }
}
