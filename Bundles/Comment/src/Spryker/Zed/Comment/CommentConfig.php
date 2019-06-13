<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Comment\CommentConfig getSharedConfig()
 */
class CommentConfig extends AbstractBundleConfig
{
    public const COMMENT_TAG_ATTACHED = 'attached';

    public const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';
    public const COMMENT_THREAD_SALES_ORDER_OWNER_TYPE = 'sales_order';
}
