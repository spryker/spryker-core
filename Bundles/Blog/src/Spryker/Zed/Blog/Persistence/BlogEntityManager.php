<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Generated\Shared\Transfer\SpyBlogCommentEntityTransfer;
use Generated\Shared\Transfer\SpyBlogEntityTransfer;
use Orm\Zed\Blog\Persistence\SpyBlog;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
class BlogEntityManager extends AbstractEntityManager implements BlogEntityManagerInterface, EntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyBlogEntityTransfer $blogEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function saveBlog(SpyBlogEntityTransfer $blogEntityTransfer)
    {
       return $this->save($blogEntityTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyBlogCommentEntityTransfer $blogCommentEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogCommentEntityTransfer
     */
    public function saveBlogComment(SpyBlogCommentEntityTransfer $blogCommentEntityTransfer)
    {
        return $this->save($blogCommentEntityTransfer);
    }

    /**
     * @api
     *
     * @param int $idBlog
     */
    public function deleteBlogById($idBlog)
    {
        $this->getFactory()
            ->createBlogQuery()
            ->filterByIdBlog($idBlog)
            ->delete();
    }

    /**
     * @api
     *
     * @param int $idComment
     */
    public function deleteCommentById($idComment)
    {
        $this->getFactory()
            ->createBlogCommentQuery()
            ->filterByIdBlogComment($idComment)
            ->delete();
    }

    /**
     * @api
     *
     * @param int $idBlogCustomer
     */
    public function deleteBlogCustomerById($idBlogCustomer)
    {
        $this->getFactory()
            ->createBlogCustomerQuery()
            ->filterByIdBlogCustomer($idBlogCustomer)
            ->delete();
    }
}
