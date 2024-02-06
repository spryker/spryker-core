<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl;

use Codeception\Actor;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroup;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class AclBusinessTester extends Actor
{
    use _generated\AclBusinessTesterActions;

    /**
     * @param int $idUser
     * @param int $idAclGroup
     *
     * @return void
     */
    public function haveAclUserHasGroup(int $idUser, int $idAclGroup): void
    {
        (new SpyAclUserHasGroup())
            ->setFkUser($idUser)
            ->setFkAclGroup($idAclGroup)
            ->save();
    }

    /**
     * @return void
     */
    public function ensureAclUserHasGroupTableIsEmpty(): void
    {
        $this->getAclUserHasGroupQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    protected function getAclUserHasGroupQuery(): SpyAclUserHasGroupQuery
    {
        return SpyAclUserHasGroupQuery::create();
    }
}
