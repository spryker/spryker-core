<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Zed\User\PageObject\UserListPage;
use SprykerTest\Zed\User\UserCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Communication
 * @group Controller
 * @group UserEditCest
 * Add your own group annotations below this line
 */
class UserEditCest
{
    /**
     * @param \SprykerTest\Zed\User\UserCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(UserCommunicationTester $i): void
    {
        $i->listDataTable(UserListPage::URL . '/index/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Users / Users / Edit User');
    }

    /**
     * @param \SprykerTest\Zed\User\UserCommunicationTester $i
     *
     * @return void
     */
    public function editUser(UserCommunicationTester $i): void
    {
        $formData = [
            UserTransfer::FIRST_NAME => 'John',
            UserTransfer::LAST_NAME => 'Doe',
            UserTransfer::USERNAME => 'johndoe@spryker.com',
            UserTransfer::PASSWORD => 'qwerty',
        ];

        $userTransfer = $i->haveUser($formData);

        $i->amOnPage('/user/edit/update?id-user=' . $userTransfer->getIdUser());

        $formData[UserTransfer::FIRST_NAME] = 'Jack';

        $i->submitForm(['name' => 'user'], $formData);

        $i->seeResponseCodeIs(302);
        $i->amOnPage('/user');
    }

    /**
     * @param \SprykerTest\Zed\User\UserCommunicationTester $i
     *
     * @return void
     */
    public function editUserWithInvalidEmailAndFail(UserCommunicationTester $i): void
    {
        $formData = [
            UserTransfer::FIRST_NAME => 'John',
            UserTransfer::LAST_NAME => 'Doe',
            UserTransfer::USERNAME => '><h1>r</h1>@tim-philipp-schaefers.de',
            UserTransfer::PASSWORD => 'qwerty',
        ];

        $userTransfer = $i->haveUser($formData);

        $i->amOnPage('/user/edit/update?id-user=' . $userTransfer->getIdUser());

        $formData[UserTransfer::FIRST_NAME] = 'Jack';

        $i->submitForm(['name' => 'user'], $formData);
        $i->expect('I am back on the form page');
        $i->seeCurrentUrlEquals('/user/edit/update?id-user=' . $userTransfer->getIdUser());
        $i->seeInSource('This value is not a valid email address.');
    }
}
