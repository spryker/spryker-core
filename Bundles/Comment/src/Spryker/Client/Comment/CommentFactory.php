<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface;
use Spryker\Client\Comment\Writer\CommentTagWriter;
use Spryker\Client\Comment\Writer\CommentTagWriterInterface;
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
    public function createZedCommentStub(): CommentStubInterface
    {
        return new CommentStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\Comment\Writer\CommentTagWriterInterface
     */
    public function createCommentTagWriter(): CommentTagWriterInterface
    {
        return new CommentTagWriter(
            $this->createZedCommentStub(),
            $this->getModuleConfig()
        );
    }

    /**
     * @return \Spryker\Client\Comment\CommentConfig
     */
    public function getModuleConfig(): CommentConfig
    {
        /** @var \Spryker\Client\Comment\CommentConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface
     */
    public function getZedRequestClient(): CommentToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
