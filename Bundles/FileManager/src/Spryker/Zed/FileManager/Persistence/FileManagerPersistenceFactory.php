<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Orm\Zed\Cms\Persistence\SpyFileInfoLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyFileInfoQuery;
use Orm\Zed\Cms\Persistence\SpyFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface getQueryContainer()
 */
class FileManagerPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function createFileQuery()
    {
        return SpyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function createFileInfoQuery()
    {
        return SpyFileInfoQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoLocalizedAttributesQuery
     */
    public function createFileInfoLocalizedAttributesQuery()
    {
        return SpyFileInfoLocalizedAttributesQuery::create();
    }
}
