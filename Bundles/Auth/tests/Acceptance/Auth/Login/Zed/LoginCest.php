<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Auth\Login\Zed;

use Acceptance\Auth\Login\Zed\PageObject\LoginPage;
use Acceptance\Auth\Login\Zed\Tester\LoginTester;

/**
 * @group Acceptance
 * @group Auth
 * @group Login
 * @group Zed
 * @group LoginCest
 */
class LoginCest
{

    /**
     * @group positive
     *
     * @param \Acceptance\Auth\Login\Zed\Tester\LoginTester $i
     *
     * @return void
     */
    public function testLoginWithValidCredentialsShouldRedirectToHomepage(LoginTester $i)
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
     * @param \Acceptance\Auth\Login\Zed\Tester\LoginTester $i
     *
     * @return void
     */
    public function testLoginWithInvalidUsernameShouldShowErrorMessage(LoginTester $i)
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
     * @param \Acceptance\Auth\Login\Zed\Tester\LoginTester $i
     *
     * @return void
     */
    public function testLoginWithInvalidPasswordShouldShowErrorMessage(LoginTester $i)
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
     * @param \Acceptance\Auth\Login\Zed\Tester\LoginTester $i
     *
     * @return void
     */
    public function testLoginWithoutUsernameShouldShowErrorMessageInFom(LoginTester $i)
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
     * @param \Acceptance\Auth\Login\Zed\Tester\LoginTester $i
     *
     * @return void
     */
    public function testLoginWithoutPasswordShouldShowErrorMessageInFom(LoginTester $i)
    {
        $i->wantTo('Login the system');
        $i->amGoingTo('try to log in without password');
        $i->expect('show error message in form');

        $i->doLogin(LoginPage::ADMIN_USERNAME, '');
        $i->see(LoginPage::ERROR_MESSAGE_EMPTY_FIELD);
    }

}
