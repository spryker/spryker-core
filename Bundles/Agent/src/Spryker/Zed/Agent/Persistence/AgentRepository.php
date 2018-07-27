<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Agent\Persistence\AgentPersistenceFactory getFactory()
 */
class AgentRepository extends AbstractRepository implements AgentRepositoryInterface
{
    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function findAgentByUsername(string $username): UserTransfer
    {
        $userEntity = $this->getFactory()
            ->createUserQuery()
            ->filterByIsAgent(true)
            ->filterByUsername($username)
            ->findOne();

        $userTransfer = new UserTransfer();

        if ($userEntity === null) {
            return $userTransfer;
        }

        return $userTransfer->fromArray($userEntity->toArray(), true);
    }
}
