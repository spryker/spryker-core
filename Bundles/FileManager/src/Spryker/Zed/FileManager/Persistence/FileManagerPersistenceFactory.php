<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributesQuery;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributesQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery;
use Spryker\Zed\FileManager\Persistence\Mapper\FileManagerMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface getQueryContainer()
 */
class FileManagerPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function createFileQuery()
    {
        return SpyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery
     */
    public function createMimeTypeQuery()
    {
        return SpyMimeTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function createFileInfoQuery()
    {
        return SpyFileInfoQuery::create();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributesQuery
     */
    public function createFileLocalizedAttributesQuery()
    {
        return SpyFileLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function createFileDirectoryQuery()
    {
        return SpyFileDirectoryQuery::create();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributesQuery
     */
    public function createFileDirectoryLocalizedAttributesQuery()
    {
        return SpyFileDirectoryLocalizedAttributesQuery::create();
    }

    /**
     * @return \Spryker\Zed\FileManager\Persistence\Mapper\FileManagerMapperInterface
     */
    public function createFileManagerMapper()
    {
        return new FileManagerMapper();
    }
}
