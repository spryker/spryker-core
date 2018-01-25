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
     * @param array $commentArray
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function toTransfer(array $commentArray, BlogCommentTransfer $blogCommentTransfer)
    {
        $blogCommentTransfer->fromArray($commentArray, true);

        return $blogCommentTransfer;
    }
}
