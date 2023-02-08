<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UserLocale\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleDependencyProvider;
use SprykerTest\Zed\UserLocale\UserLocaleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group UserLocale
 * @group Business
 * @group Facade
 * @group UserLocaleFacadeTest
 * Add your own group annotations below this line
 */
class UserLocaleFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\UserLocale\UserLocaleBusinessTester
     */
    protected UserLocaleBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandUserCollectionWithLocaleExpandsUserTransferWithCurrentLocaleWhenLocaleDataIsMissing(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $this->tester->setDependency(UserLocaleDependencyProvider::FACADE_LOCALE, $this->getLocaleFacadeMock($localeTransfer));

        $userTransfer = (new UserBuilder([
            UserTransfer::LOCALE_NAME => null,
            UserTransfer::FK_LOCALE => null,
        ]))->build();
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->expandUserCollectionWithLocale($userCollectionTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $this->assertSame($localeTransfer->getIdLocaleOrFail(), $userTransfer->getFkLocale());
        $this->assertSame($localeTransfer->getLocaleNameOrFail(), $userTransfer->getLocaleName());
    }

    /**
     * @return void
     */
    public function testExpandUserCollectionWithLocaleExpandsUserTransferWithLocaleNameWhenFkLocaleIsSet(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $this->tester->setDependency(UserLocaleDependencyProvider::FACADE_LOCALE, $this->getLocaleFacadeMock($localeTransfer));

        $userTransfer = (new UserBuilder([
            UserTransfer::FK_LOCALE => $localeTransfer->getIdLocaleOrFail(),
            UserTransfer::LOCALE_NAME => null,
        ]))->build();
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->expandUserCollectionWithLocale($userCollectionTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $this->assertSame($localeTransfer->getIdLocaleOrFail(), $userTransfer->getFkLocale());
        $this->assertSame($localeTransfer->getLocaleNameOrFail(), $userTransfer->getLocaleName());
    }

    /**
     * @return void
     */
    public function testExpandUserCollectionWithLocaleExpandsUserTransferWithFkLocaleWhenLocaleNameIsSet(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $this->tester->setDependency(UserLocaleDependencyProvider::FACADE_LOCALE, $this->getLocaleFacadeMock($localeTransfer));

        $userTransfer = (new UserBuilder([
            UserTransfer::LOCALE_NAME => $localeTransfer->getLocaleNameOrFail(),
            UserTransfer::FK_LOCALE => null,
        ]))->build();
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->expandUserCollectionWithLocale($userCollectionTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $this->assertSame($localeTransfer->getIdLocaleOrFail(), $userTransfer->getFkLocale());
        $this->assertSame($localeTransfer->getLocaleNameOrFail(), $userTransfer->getLocaleName());
    }

    /**
     * @return void
     */
    public function testExpandUserCollectionWithLocaleDoesNothingWhenLocaleDataIsSet(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();

        $localeFacadeMock = $this->getLocaleFacadeMock($localeTransfer);
        $localeFacadeMock->expects($this->never())->method('getCurrentLocale');
        $localeFacadeMock->expects($this->never())->method('getAvailableLocales');
        $this->tester->setDependency(UserLocaleDependencyProvider::FACADE_LOCALE, $this->getLocaleFacadeMock($localeTransfer));

        $userTransfer = (new UserBuilder([
            UserTransfer::LOCALE_NAME => $localeTransfer->getLocaleNameOrFail(),
            UserTransfer::FK_LOCALE => $localeTransfer->getIdLocaleOrFail(),
        ]))->build();
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->expandUserCollectionWithLocale($userCollectionTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $this->assertSame($localeTransfer->getIdLocaleOrFail(), $userTransfer->getFkLocale());
        $this->assertSame($localeTransfer->getLocaleNameOrFail(), $userTransfer->getLocaleName());
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface
     */
    protected function getLocaleFacadeMock(LocaleTransfer $localeTransfer): UserLocaleToLocaleFacadeBridgeInterface
    {
        $localeFacadeMock = $this->getMockBuilder(UserLocaleToLocaleFacadeBridgeInterface::class)->getMock();
        $localeFacadeMock
            ->method('getCurrentLocale')
            ->willReturn($localeTransfer);
        $localeFacadeMock
            ->method('getAvailableLocales')
            ->willReturn([
                $localeTransfer->getIdLocaleOrFail() => $localeTransfer->getLocaleNameOrFail(),
            ]);

        return $localeFacadeMock;
    }
}
