<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Comment;

use Generated\Shared\Transfer\CommentTransfer;

interface OrderCommentSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderComment
     */
    public function save(CommentTransfer $commentTransfer);
}
