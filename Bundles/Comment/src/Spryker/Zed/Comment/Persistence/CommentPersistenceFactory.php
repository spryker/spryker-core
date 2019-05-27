<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Orm\Zed\Comment\Persistence\SpyCommentVersionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Comment\Dependency\Service\CommentToUtilEncodingServiceInterface;
use Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentMapper;
use Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentVersionMapper;
use Spryker\Zed\Comment\CommentDependencyProvider;

/**
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 */
class CommentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    public function getCommentPropelQuery(): SpyCommentQuery
    {
        return SpyCommentQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentVersionQuery
     */
    public function getCommentVersionPropelQuery(): SpyCommentVersionQuery
    {
        return SpyCommentVersionQuery::create();
    }

    /**
     * @return \Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentMapper
     */
    public function createCommentMapper(): CommentMapper
    {
        return new CommentMapper();
    }

    /**
     * @return \Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentVersionMapper
     */
    public function createCommentVersionMapper(): CommentVersionMapper
    {
        return new CommentVersionMapper(
            $this->getConfig(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Dependency\Service\CommentToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CommentToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
