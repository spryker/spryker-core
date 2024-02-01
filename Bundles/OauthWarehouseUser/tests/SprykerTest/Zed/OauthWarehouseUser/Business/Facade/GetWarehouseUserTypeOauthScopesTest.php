<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthWarehouseUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Zed\OauthWarehouseUser\OauthWarehouseUserTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthWarehouseUser
 * @group Business
 * @group Facade
 * @group GetWarehouseUserTypeOauthScopesTest
 * Add your own group annotations below this line
 */
class GetWarehouseUserTypeOauthScopesTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig::SCOPE_WAREHOUSE_USER
     *
     * @var string
     */
    protected const SCOPE_WAREHOUSE_USER = 'warehouse-user';

    /**
     * @var \SprykerTest\Zed\OauthWarehouseUser\OauthWarehouseUserTester
     */
    protected OauthWarehouseUserTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnConfiguratedWarehouseUserScopes(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $userIdentifierTransfer = (new UserIdentifierTransfer())->setIdUser($userTransfer->getIdUserOrFail());

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getWarehouseUserTypeOauthScopes($userIdentifierTransfer);

        // Assert
        $this->assertSame($oauthScopeTransfers[0]->getIdentifier(), static::SCOPE_WAREHOUSE_USER);
    }

    /**
     * @return void
     */
    public function testShouldNotReturnWarehouseUserScopesWhileUserIsUnknown(): void
    {
        // Arrange
        $userTransfer = (new UserIdentifierTransfer())->setIdUser(666);
        $userIdentifierTransfer = (new UserIdentifierTransfer())->setIdUser($userTransfer->getIdUserOrFail());

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getWarehouseUserTypeOauthScopes($userIdentifierTransfer);

        // Assert
        $this->assertEmpty($oauthScopeTransfers);
    }

    /**
     * @return void
     */
    public function testShouldNotReturnWarehouseUserScopesWhileUserIsNotAWarehouseUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => false]);
        $userIdentifierTransfer = (new UserIdentifierTransfer())->setIdUser($userTransfer->getIdUserOrFail());

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getWarehouseUserTypeOauthScopes($userIdentifierTransfer);

        // Assert
        $this->assertEmpty($oauthScopeTransfers);
    }
}
