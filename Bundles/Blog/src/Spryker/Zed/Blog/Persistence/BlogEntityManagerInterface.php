<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;


/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
interface BlogEntityManagerInterface
{
    public function save(TransferInterface $transfer);

    public function saveBlog(BlogTransfer $blogTransfer);

    /**
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function saveBlogComment(BlogCommentTransfer $blogCommentTransfer);
}
