<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Orm\Zed\Blog\Persistence\SpyBlogCommentQuery;
use Orm\Zed\Blog\Persistence\SpyBlogCustomerQuery;
use Orm\Zed\Blog\Persistence\SpyBlogQuery;
use Spryker\Zed\Blog\Persistence\Plugins\BlogPluginExecutor;
use Spryker\Zed\Blog\Persistence\Propel\Mapper\BlogMapper;
use Spryker\Zed\Blog\Persistence\Propel\Mapper\CommentMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Blog\BlogDependencyProvider;

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
     * @return \Orm\Zed\Blog\Persistence\SpyBlogCommentQuery
     */
    public function createBlogCommentQuery()
    {
        return SpyBlogCommentQuery::create();
    }

    /**
     * @return \Orm\Zed\Blog\Persistence\SpyBlogCustomerQuery
     */
    public function createBlogCustomerQuery()
    {
        return SpyBlogCustomerQuery::create();
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

    /**
     * @return \Spryker\Zed\Blog\Persistence\Plugins\BlogPluginExecutorInterface
     */
    public function createBlogPluginExecutor()
    {
        return new BlogPluginExecutor($this->getBlogPreSavePlugins(), $this->getBlogPostSavePlugins());
    }

    /**
     * @return \Spryker\Zed\Blog\Dependency\Plugin\PreSaveBlogPluginInterface[]
     */
    protected function getBlogPreSavePlugins()
    {
        return $this->getProvidedDependency(BlogDependencyProvider::PLUGIN_PRE_SAVE_BLOG);
    }

    /**
     * @return \Spryker\Zed\Blog\Dependency\Plugin\PostSaveBlogPluginInterface[]
     */
    protected function getBlogPostSavePlugins()
    {
        return $this->getProvidedDependency(BlogDependencyProvider::PLUGIN_POST_SAVE_BLOG);
    }
}
