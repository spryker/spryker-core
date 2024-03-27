<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Persistence;

use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Spryker\Zed\CommentUserConnector\CommentUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CommentUserConnector\CommentUserConnectorConfig getConfig()
 * @method \Spryker\Zed\CommentUserConnector\Persistence\CommentUserConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CommentUserConnector\Persistence\CommentUserConnectorEntityManagerInterface getEntityManager()
 */
class CommentUserConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    public function getCommentQuery(): SpyCommentQuery
    {
        return $this->getProvidedDependency(CommentUserConnectorDependencyProvider::PROPEL_QUERY_COMMENT);
    }
}
