<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence;

use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\Content\Persistence\Propel\Mapper\ContentMapper;
use Spryker\Zed\Content\Persistence\Propel\Mapper\ContentMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Content\ContentConfig getConfig()
 * @method \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Content\Persistence\ContentRepositoryInterface getRepository()
 */
class ContentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    public function createContentQuery(): SpyContentQuery
    {
        return SpyContentQuery::create();
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentLocalizedQuery
     */
    public function createContentLocalizedQuery(): SpyContentLocalizedQuery
    {
        return SpyContentLocalizedQuery::create();
    }

    /**
     * @return \Spryker\Zed\Content\Persistence\Propel\Mapper\ContentMapperInterface
     */
    public function createContentMapper(): ContentMapperInterface
    {
        return new ContentMapper();
    }
}
