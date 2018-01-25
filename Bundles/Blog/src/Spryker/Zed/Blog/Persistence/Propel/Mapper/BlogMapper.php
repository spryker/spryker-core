<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\BlogTransfer;
use Orm\Zed\Blog\Persistence\SpyBlog;

class BlogMapper
{
    /**
     * @param array $blog
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function toTransfer(array $blog, BlogTransfer $blogTransfer)
    {
        $blogTransfer->fromArray($blog, true);

        return $blogTransfer;
    }
}
