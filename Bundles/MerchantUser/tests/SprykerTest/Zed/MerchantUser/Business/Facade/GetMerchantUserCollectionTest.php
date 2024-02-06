<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserSearchConditionsTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\UserTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantUser
 * @group Business
 * @group Facade
 * @group GetMerchantUserCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantUserCollectionTest extends Unit
{
    /**
     * @uses \Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_NAME = 'spy_merchant.name';

    /**
     * @var \SprykerTest\Zed\MerchantUser\MerchantUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantUserTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testSearchesByMerchantName(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'abc']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'bcd']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'cde']),
            $this->tester->haveUser(),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())->setMerchantName('bc'),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(2, $merchantUserTransfers);
        $expectedMerchantNames = ['abc', 'bcd'];
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->assertContains($merchantUserTransfer->getMerchant()->getName(), $expectedMerchantNames);
        }
    }

    /**
     * @return void
     */
    public function testSearchesByUserFirstName(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::FIRST_NAME => 'abc']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::FIRST_NAME => 'bcd']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::FIRST_NAME => 'cde']),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())->setUserFirstName('bc'),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(2, $merchantUserTransfers);
        $expectedUserFirstNames = ['abc', 'bcd'];
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->assertContains($merchantUserTransfer->getUser()->getFirstName(), $expectedUserFirstNames);
        }
    }

    /**
     * @return void
     */
    public function testSearchesByUserLastName(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::LAST_NAME => 'abc']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::LAST_NAME => 'bcd']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::LAST_NAME => 'cde']),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())->setUserLastName('bc'),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(2, $merchantUserTransfers);
        $expectedUserLastNames = ['abc', 'bcd'];
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->assertContains($merchantUserTransfer->getUser()->getLastName(), $expectedUserLastNames);
        }
    }

    /**
     * @return void
     */
    public function testSearchesByUsername(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::USERNAME => 'abc']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::USERNAME => 'bcd']),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser([UserTransfer::USERNAME => 'cde']),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())->setUsername('bc'),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(2, $merchantUserTransfers);
        $expectedUsernames = ['abc', 'bcd'];
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->assertContains($merchantUserTransfer->getUser()->getUsername(), $expectedUsernames);
        }
    }

    /**
     * @return void
     */
    public function testSearchesByDifferentEqualConditions(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'abc']),
            $this->tester->haveUser([
                UserTransfer::FIRST_NAME => 'cde',
                UserTransfer::LAST_NAME => 'def',
                UserTransfer::USERNAME => 'efg',
            ]),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'cde']),
            $this->tester->haveUser([
                UserTransfer::FIRST_NAME => 'abc',
                UserTransfer::LAST_NAME => 'efg',
                UserTransfer::USERNAME => 'def',
            ]),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'fgh']),
            $this->tester->haveUser([
                UserTransfer::FIRST_NAME => 'efg',
                UserTransfer::LAST_NAME => 'def',
                UserTransfer::USERNAME => 'fgh',
            ]),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'def']),
            $this->tester->haveUser([
                UserTransfer::FIRST_NAME => 'efg',
                UserTransfer::LAST_NAME => 'abc',
                UserTransfer::USERNAME => 'cde',
            ]),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'efg']),
            $this->tester->haveUser([
                UserTransfer::FIRST_NAME => 'cde',
                UserTransfer::LAST_NAME => 'def',
                UserTransfer::USERNAME => 'abc',
            ]),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setMerchantUserSearchConditions(
            (new MerchantUserSearchConditionsTransfer())
                ->setMerchantName('bc')
                ->setUserFirstName('bc')
                ->setUserLastName('bc')
                ->setUsername('bc'),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(4, $merchantUserTransfers);
        $expectedMerchantNames = ['abc', 'cde', 'def', 'efg'];
        foreach ($merchantUserTransfers as $merchantUserTransfer) {
            $this->assertContains($merchantUserTransfer->getMerchant()->getName(), $expectedMerchantNames);
        }
    }

    /**
     * @return void
     */
    public function testReturnsCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'bca']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'cba']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'abc']),
            $this->tester->haveUser(),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(static::COL_NAME)
                ->setIsAscending(true),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(3, $merchantUserTransfers);
        $this->assertSame('abc', $merchantUserTransfers->getIterator()->offsetGet(0)->getMerchant()->getName());
        $this->assertSame('bca', $merchantUserTransfers->getIterator()->offsetGet(1)->getMerchant()->getName());
        $this->assertSame('cba', $merchantUserTransfers->getIterator()->offsetGet(2)->getMerchant()->getName());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'bca']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'cba']),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::NAME => 'abc']),
            $this->tester->haveUser(),
        );
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(static::COL_NAME)
                ->setIsAscending(false),
        );

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(3, $merchantUserTransfers);
        $this->assertSame('cba', $merchantUserTransfers->getIterator()->offsetGet(0)->getMerchant()->getName());
        $this->assertSame('bca', $merchantUserTransfers->getIterator()->offsetGet(1)->getMerchant()->getName());
        $this->assertSame('abc', $merchantUserTransfers->getIterator()->offsetGet(2)->getMerchant()->getName());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionSortedByOffsetAndLimit(): void
    {
        // Arrange
        $this->tester->haveMerchantUser($this->tester->haveMerchant(), $this->tester->haveUser());
        $merchantUserTransfer = $this->tester->haveMerchantUser(
            $this->tester->haveMerchant(),
            $this->tester->haveUser(),
        );
        $this->tester->haveMerchantUser($this->tester->haveMerchant(), $this->tester->haveUser());
        $paginationTransfer = (new PaginationTransfer())->setOffset(1)->setLimit(1);
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $merchantUserTransfers = $this->tester
            ->getFacade()
            ->getMerchantUserCollection($merchantUserCriteriaTransfer)
            ->getMerchantUsers();

        // Assert
        $this->assertCount(1, $merchantUserTransfers);
        $this->assertSame(
            $merchantUserTransfer->getIdMerchantUser(),
            $merchantUserTransfers->getIterator()->offsetGet(0)->getIdMerchantUser(),
        );
    }
}
