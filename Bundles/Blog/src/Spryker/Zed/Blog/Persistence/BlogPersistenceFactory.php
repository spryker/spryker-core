<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Orm\Zed\Blog\Persistence\SpyBlogQuery;
use Spryker\Zed\Blog\Persistence\Propel\Mapper\BlogMapper;
use Spryker\Zed\Blog\Persistence\Propel\Mapper\CommentMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class BlogPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Blog\Persistence\SpyBlogQuery
     */
    public function createBlogQuery()
    {
        return SpyBlogQuery::create();
    }

    /**
     * @return \Spryker\Zed\Blog\Persistence\Propel\Mapper\BlogMapper
     */
    public function createBlogMapper()
    {
        return new BlogMapper();
    }

    /**
     * @return \Spryker\Zed\Blog\Persistence\Propel\Mapper\CommentMapper
     */
    public function createCommentMapper()
    {
        return new CommentMapper();
    }
}
