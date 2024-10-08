<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\SpyUserQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface;

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
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\User\PHPMD)
 */
class UserBusinessTester extends Actor
{
    use _generated\UserBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureUserTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getUserQuery());
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserByUserName(string $username): ?UserTransfer
    {
        $userEntity = $this->getUserQuery()->findOneByUsername($username);
        if ($userEntity === null) {
            return null;
        }

        return (new UserTransfer())->fromArray($userEntity->toArray(), true);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserTransfer(): UserTransfer
    {
        return (new UserBuilder())->build();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface
     */
    public function getUserPostUpdatePluginMock(UserTransfer $userTransfer): UserPostUpdatePluginInterface
    {
        $userPostCreatePluginMock = Stub::makeEmpty(UserPostUpdatePluginInterface::class);
        $userPostCreatePluginMock->expects(new InvokedCountMatcher(1))
            ->method('postUpdate')
            ->willReturn((new UserCollectionResponseTransfer())->addUser($userTransfer));

        return $userPostCreatePluginMock;
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    protected function getUserQuery(): SpyUserQuery
    {
        return SpyUserQuery::create();
    }
}
