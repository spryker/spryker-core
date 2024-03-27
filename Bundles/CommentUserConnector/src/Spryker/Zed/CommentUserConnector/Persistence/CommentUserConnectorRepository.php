<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CommentUserConnector\Persistence\CommentUserConnectorPersistenceFactory getFactory()
 */
class CommentUserConnectorRepository extends AbstractRepository implements CommentUserConnectorRepositoryInterface
{
    /**
     * @param int $idUser
     * @param int $idComment
     *
     * @return bool
     */
    public function isUserCommentAuthor(int $idUser, int $idComment): bool
    {
        return $this->getFactory()
            ->getCommentQuery()
            ->filterByIdComment($idComment)
            ->filterByFkUser($idUser)
            ->exists();
    }
}
