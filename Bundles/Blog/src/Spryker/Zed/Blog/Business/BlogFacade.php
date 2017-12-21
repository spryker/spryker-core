<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Business;

use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Blog\Business\BlogBusinessFactory getFactory()
 */
class BlogFacade extends AbstractFacade
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function save(BlogTransfer $blogTransfer)
    {
        return $this->getFactory()
            ->createBlog()
            ->save($blogTransfer);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function findBlogById($id)
    {
        return $this->getFactory()
            ->createBlog()
            ->findBlogById($id);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCriteriaFilterTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        return $this->getFactory()
            ->createBlog()
            ->filterBlogPosts($blogCriteriaFilterTransfer);
    }

}
