<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Zed\User\UserDependencyProvider;
use SprykerTest\Zed\User\UserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Business
 * @group Facade
 * @group RemoveUserTest
 * Add your own group annotations below this line
 */
class RemoveUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\User\UserBusinessTester
     */
    public UserBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldExecuteStackOfUserPostUpdatePlugins(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $this->tester->setDependency(
            UserDependencyProvider::PLUGINS_USER_POST_UPDATE,
            [$this->tester->getUserPostUpdatePluginMock($userTransfer)],
        );

        // Act
        $this->tester->getFacade()->removeUser($userTransfer->getIdUserOrFail());
    }
}
