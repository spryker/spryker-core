<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthUserConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthUserTransfer;
use SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthUserConnector
 * @group Business
 * @group GetOauthUserTest
 * Add your own group annotations below this line
 */
class GetOauthUserTest extends Unit
{
    /**
     * @var string
     */
    protected const USER_USERNAME = 'harald@spryker.com';

    /**
     * @var string
     */
    protected const USER_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const USER_PASSWORD_INVALID = 'wrong password';

    /**
     * @var string
     */
    protected const USER_USERNAME_UNKNOWN = 'unknown@spryker.com';

    /**
     * @var \SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester
     */
    protected OauthUserConnectorBusinessTester $tester;

    /**
     * @dataProvider getOauthUserDataProvider
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     * @param bool $expectedIsSuccess
     *
     * @return void
     */
    public function testShouldReturnCorrectOauthUserTransfer(
        OauthUserTransfer $oauthUserTransfer,
        bool $expectedIsSuccess
    ): void {
        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getOauthUser($oauthUserTransfer);

        //Assert
        $this->assertSame($expectedIsSuccess, $oauthUserTransfer->getIsSuccess());
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\OauthUserTransfer, bool>>
     */
    protected function getOauthUserDataProvider(): array
    {
        return [
            'should return authorized oauth user' => [
                $this->createOauthUserTransfer(),
                true,
            ],
            'should return unauthorized oauth user while credentials are invalid' => [
                $this->createOauthUserTransfer()->setPassword(static::USER_PASSWORD_INVALID),
                false,
            ],
            'should return unauthorized oauth user while user is unknown' => [
                $this->createOauthUserTransfer()->setUsername(static::USER_USERNAME_UNKNOWN),
                false,
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    protected function createOauthUserTransfer(): OauthUserTransfer
    {
        return (new OauthUserTransfer())
            ->setUsername(static::USER_USERNAME)
            ->setPassword(static::USER_PASSWORD);
    }
}
