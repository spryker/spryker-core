<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;

interface BlogRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\BlogTransfer|null
     */
    public function findBlogById($id);

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function persistBlog(BlogTransfer $blogTransfer);

    /**
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function persistBlogComment(BlogCommentTransfer $blogCommentTransfer);

    /**
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCriteriaFilterTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer);
}
