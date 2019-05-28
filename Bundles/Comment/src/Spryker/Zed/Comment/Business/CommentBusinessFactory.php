<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Spryker\Zed\Comment\Business\Writer\CommentWriter;
use Spryker\Zed\Comment\Business\Writer\CommentWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 */
class CommentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentWriterInterface
     */
    public function createCommentWriter(): CommentWriterInterface
    {
        return new CommentWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }
}
