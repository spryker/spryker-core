<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery;
use Spryker\Zed\FileManagerStorage\Persistence\Mapper\FileManagerStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class FileManagerStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function createFileQuery()
    {
        return new SpyFileQuery();
    }

    /**
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function createFileStorageQuery()
    {
        return SpyFileStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\FileManagerStorage\Persistence\Mapper\FileManagerStorageMapperInterface
     */
    public function createFileManagerStorageMapper()
    {
        return new FileManagerStorageMapper();
    }
}
