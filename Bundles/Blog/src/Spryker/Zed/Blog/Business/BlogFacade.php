<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Business;

use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Generated\Shared\Transfer\SpyBlogEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Blog\Business\BlogBusinessFactory getFactory()
 * @method \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface getRepository()
 * @method \Spryker\Zed\Blog\Persistence\BlogEntityManagerInterface getEntityManager()
 */
class BlogFacade extends AbstractFacade
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyBlogEntityTransfer $blogEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function save(SpyBlogEntityTransfer $blogEntityTransfer)
    {
        return $this->getEntityManager()
            ->saveBlog($blogEntityTransfer);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function findBlogByName($name)
    {
        return $this->getFactory()
            ->createBlog()
            ->findBlogByName($name);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        return $this->getFactory()
            ->createBlog()
            ->filterBlogPosts($blogCriteriaFilterTransfer);
    }
}
