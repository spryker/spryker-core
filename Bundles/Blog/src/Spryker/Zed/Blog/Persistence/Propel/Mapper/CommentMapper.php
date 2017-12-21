<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Orm\Zed\Blog\Persistence\SpyBlogComment;

class CommentMapper
{
    /**
     * @param \Orm\Zed\Blog\Persistence\SpyBlogComment $blogCommentEntity
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function fromEntityToTransfer(SpyBlogComment $blogCommentEntity, BlogCommentTransfer $blogCommentTransfer)
    {
        $blogCommentTransfer->fromArray($blogCommentEntity->toArray(), true);

        return $blogCommentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     * @param \Orm\Zed\Blog\Persistence\SpyBlogComment $blogCommentEntity
     *
     * @return \Orm\Zed\Blog\Persistence\SpyBlogComment
     */
    public function fromTransferToEntity(BlogCommentTransfer $blogCommentTransfer, SpyBlogComment $blogCommentEntity)
    {
        $blogCommentEntity->fromArray($blogCommentTransfer->toArray());

        return $blogCommentEntity;
    }
}
