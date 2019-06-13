<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface;
use Spryker\Client\Comment\Zed\CommentStub;
use Spryker\Client\Comment\Zed\CommentStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Comment\CommentConfig getConfig()
 */
class CommentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Comment\Zed\CommentStubInterface
     */
    public function createCommentStub(): CommentStubInterface
    {
        return new CommentStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface
     */
    public function getZedRequestClient(): CommentToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
