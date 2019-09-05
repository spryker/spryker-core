<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group GetMerchantDataTest
 * Add your own group annotations below this line
 */
class GetMerchantDataTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindOneMerchant(): void
    {
        // Arrange
        $expectedMerchant = $this->tester->haveMerchant();

        // Act
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchantById = $this->tester->getFacade()->findOne($merchantCriteriaFilterTransfer);

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setEmail($expectedMerchant->getEmail());

        $actualMerchantByEmail = $this->tester->getFacade()->findOne($merchantCriteriaFilterTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchantById);
        $this->assertEquals($expectedMerchant, $actualMerchantByEmail);
    }

    /**
     * @return void
     */
    public function testFindMerchants(): void
    {
        // Arrange
        $this->tester->truncateMerchantRelations();

        $this->tester->haveMerchant();
        $this->tester->haveMerchant();

        // Act
        $merchantCollectionWithoutCriteriaTransfer = $this->tester->getFacade()->find();

        // Arrange
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setPage(1);
        $paginationTransfer->setMaxPerPage(1);
        $merchantCriteriaFilterTransfer->setPagination($paginationTransfer);

        // Act
        $merchantCollectionWithPaginationTransfer = $this->tester->getFacade()->find($merchantCriteriaFilterTransfer);

        // Arrange
        $paginationTransfer = new FilterTransfer();
        $paginationTransfer->setOrderBy(SpyMerchantTableMap::COL_ID_MERCHANT);
        $paginationTransfer->setOrderDirection('DESC');
        $merchantCriteriaFilterTransfer->setFilter($paginationTransfer);

        // Act
        $merchantCollectionWithPaginationTransfer = $this->tester->getFacade()->find($merchantCriteriaFilterTransfer);

        // Arrange
        $merchantCriteriaFilterTransfer->getFilter()->setOrderDirection('ASC');

        // Act
        $merchantCollectionOrderByNameDescTransfer = $this->tester->getFacade()->find($merchantCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $merchantCollectionWithoutCriteriaTransfer->getMerchants());
        $this->assertCount(1, $merchantCollectionWithPaginationTransfer->getMerchants());
        $this->assertCount(1, $merchantCollectionOrderByNameDescTransfer->getMerchants());

        $this->assertNotEquals($merchantCollectionOrderByNameDescTransfer->getMerchants()[0]->getIdMerchant(), $merchantCollectionWithPaginationTransfer->getMerchants()[0]->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesWillReturnArray(): void
    {
        // Act
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses($this->tester->createMerchantConfig()->getDefaultMerchantStatus());

        // Assert
        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertNotEmpty($applicableMerchantStatuses);
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesWillReturnEmptyArrayOnNotFoundCurrentStatus(): void
    {
        // Act
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses('random-status');

        // Assert
        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertEmpty($applicableMerchantStatuses);
    }
}
