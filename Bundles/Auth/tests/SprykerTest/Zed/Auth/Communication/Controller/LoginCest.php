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
 * @group LoginCest
 * Add your own group annotations below this line
 */
class LoginCest
{
    /**
     * @param \SprykerTest\Zed\Auth\AuthCommunicationTester $i
     *
     * @return void
     */
    public function testLoginFormIsVisible(AuthCommunicationTester $i)
    {
        $i->amOnPage('/auth/login');
        $i->seeElement('form', ['name' => 'auth']);
    }
}
