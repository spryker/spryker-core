<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentThreadMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 */
class CommentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThreadQuery
     */
    public function getCommentThreadPropelQuery(): SpyCommentThreadQuery
    {
        return SpyCommentThreadQuery::create();
    }

    /**
     * @return \Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentThreadMapper
     */
    public function createCommentThreadMapping(): CommentThreadMapper
    {
        return new CommentThreadMapper();
    }
}
