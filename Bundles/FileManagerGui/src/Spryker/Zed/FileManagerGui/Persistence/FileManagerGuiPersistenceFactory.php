<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FileManagerGui\Persistence\FileManagerGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FileManagerGui\FileManagerGuiConfig getConfig()
 */
class FileManagerGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function createFileDirectoryQuery()
    {
        return SpyFileDirectoryQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function createCmsPageLocalizedAttributesQuery()
    {
        return SpyCmsPageLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function createCategoryAttributeQuery()
    {
        return SpyCategoryAttributeQuery::create();
    }
}
