<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Comment;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CommentConfig extends AbstractSharedConfig
{
    /**
     * @return string[]
     */
    public function getAvailableCommentTags(): array
    {
        return [];
    }
}
