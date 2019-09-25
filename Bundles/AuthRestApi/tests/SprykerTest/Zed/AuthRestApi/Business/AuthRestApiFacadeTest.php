<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthRestApi\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AuthRestApi
 * @group Business
 * @group Facade
 * @group AuthRestApiFacadeTest
 * Add your own group annotations below this line
 */
class AuthRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AuthRestApi\AuthRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetValidOauthResponseTransfer(): void
    {
        $authRestApiFacade = $this->tester->getFacade();
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        $oauthResponseTransfer = $authRestApiFacade->createAccessToken($oauthRequestTransfer);

        $this->assertEquals($oauthResponseTransfer->getAnonymousCustomerReference(), $oauthRequestTransfer->getCustomerReference());
        $this->assertTrue($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetInvalidOauthResponseTransfer(): void
    {
        $authRestApiFacade = $this->tester->getFacade();
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        $oauthResponseTransfer = $authRestApiFacade->createAccessToken($oauthRequestTransfer);
        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }
}
