<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Communication\Controller;

use SprykerTest\Zed\Auth\AuthCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Communication
 * @group Controller
 * @group PasswordControllerCest
 * Add your own group annotations below this line
 */
class PasswordControllerCest
{
    /**
     * @param \SprykerTest\Zed\Auth\AuthCommunicationTester $i
     *
     * @return void
     */
    public function testResetPasswordRequestFormIsVisible(AuthCommunicationTester $i)
    {
        $i->amOnPage('/auth/password/reset-request');
        $i->seeElement('form', ['name' => 'reset_password']);
    }
}
