<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Presentation;

use SprykerTest\Zed\Auth\AuthPresentationTester;
use SprykerTest\Zed\Auth\PageObject\LoginPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Presentation
 * @group LoginCest
 * Add your own group annotations below this line
 */
class LoginCest
{
    /**
     * @group positive
     *
     * @param \SprykerTest\Zed\Auth\AuthPresentationTester $i
     *
     * @return void
     */
    public function testLoginWithValidCredentialsShouldRedirectToHomepage(AuthPresentationTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to login with an NON valid NAME');
        $i->expect('it is NOT possible');

        $i->doLogin(LoginPage::ADMIN_USERNAME, LoginPage::ADMIN_PASSWORD);
        $i->dontSee(LoginPage::AUTHENTICATION_FAILED);
    }

    /**
     * @group negative
     *
     * @param \SprykerTest\Zed\Auth\AuthPresentationTester $i
     *
     * @return void
     */
    public function testLoginWithInvalidUsernameShouldShowErrorMessage(AuthPresentationTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to login with an invalid username');
        $i->expect('it is NOT possible');

        $i->doLogin('*', LoginPage::ADMIN_PASSWORD);
        $i->see(LoginPage::AUTHENTICATION_FAILED);

        $i->doLogin(rand(10000, 99999) . '@spryker.com', LoginPage::ADMIN_PASSWORD);
        $i->see(LoginPage::AUTHENTICATION_FAILED);

        $i->doLogin('admin%%%', LoginPage::ADMIN_PASSWORD);
        $i->see(LoginPage::AUTHENTICATION_FAILED);
    }

    /**
     * @group negative
     *
     * @param \SprykerTest\Zed\Auth\AuthPresentationTester $i
     *
     * @return void
     */
    public function testLoginWithInvalidPasswordShouldShowErrorMessage(AuthPresentationTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to log in with an invalid password');
        $i->expect('it is NOT possible');

        $i->doLogin(LoginPage::ADMIN_USERNAME, '*');
        $i->see(LoginPage::AUTHENTICATION_FAILED);

        $i->doLogin(LoginPage::ADMIN_USERNAME, rand(3, 20));
        $i->see(LoginPage::AUTHENTICATION_FAILED);

        $i->doLogin(LoginPage::ADMIN_USERNAME, 'ch**ge123');
        $i->see(LoginPage::AUTHENTICATION_FAILED);
    }

    /**
     * @group negative
     *
     * @param \SprykerTest\Zed\Auth\AuthPresentationTester $i
     *
     * @return void
     */
    public function testLoginWithoutUsernameShouldShowErrorMessageInFom(AuthPresentationTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to log in without username');
        $i->expect('show error message in form');

        $i->doLogin('', LoginPage::ADMIN_PASSWORD);
        $i->see(LoginPage::ERROR_MESSAGE_EMPTY_FIELD);
    }

    /**
     * @group negative
     *
     * @param \SprykerTest\Zed\Auth\AuthPresentationTester $i
     *
     * @return void
     */
    public function testLoginWithoutPasswordShouldShowErrorMessageInFom(AuthPresentationTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to log in without password');
        $i->expect('show error message in form');

        $i->doLogin(LoginPage::ADMIN_USERNAME, '');
        $i->see(LoginPage::ERROR_MESSAGE_EMPTY_FIELD);
    }
}
