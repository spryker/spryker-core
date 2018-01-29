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
use Spryker\Shared\Kernel\Transfer\TransferInterface;


/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
interface BlogEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyBlogEntityTransfer $blogEntityTransfer
     *
     * @return mixed
     */
    public function saveBlog(SpyBlogEntityTransfer $blogEntityTransfer);

    /**
     *
     * @param \Generated\Shared\Transfer\SpyBlogCommentEntityTransfer $blogCommentEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogCommentEntityTransfer
     */
    public function saveBlogComment(SpyBlogCommentEntityTransfer $blogCommentEntityTransfer);

    /**
     * @api
     *
     * @param int $idBlog
     */
    public function deleteBlogById($idBlog);

    /**
     * @api
     *
     * @param int $idComment
     */
    public function deleteCommentById($idComment);

    /**
     * @api
     *
     * @param int $idBlogCustomer
     */
    public function deleteBlogCustomerById($idBlogCustomer);
}
