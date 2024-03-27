<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSearchConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationship
 * @group Business
 * @group Facade
 * @group GetMerchantRelationshipCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantRelationshipCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected MerchantRelationshipBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantRelationshipTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionWillReturnRelationshipsFilteredByOwnerCompanyBusinessUnitIds(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipBySeedData();
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData();

        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())->addIdOwnerCompanyBusinessUnit(
            $merchantRelationshipTransfer->getFkCompanyBusinessUnitOrFail(),
        );
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())->setMerchantRelationshipConditionsOrFail(
            $merchantRelationshipConditionsTransfer,
        );

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionWillReturnRelationshipsFilteredByMerchantIsActiveStatus(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData();
        $this->tester->createMerchantRelationshipBySeedData();
        $this->tester->deactivateMerchant($merchantRelationshipTransfer->getFkMerchantOrFail());

        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setIsActiveMerchant(false);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())->setMerchantRelationshipConditionsOrFail(
            $merchantRelationshipConditionsTransfer,
        );

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsMerchantRelationshipCollectionExpandedWithAssigneeBusinessUnits(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData();

        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail());
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsCollectionFilteredByCreatedAtFrom(): void
    {
        // Arrange
        $this->tester->createMerchantRelationshipBySeedData([
            MerchantRelationshipTransfer::CREATED_AT => (new DateTime('Jan 1, 2024'))->format('Y-m-d H:i:s'),
        ]);
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([
            MerchantRelationshipTransfer::CREATED_AT => (new DateTime('Jan 10, 2024'))->format('Y-m-d H:i:s'),
        ]);

        $criteriaRangeFilterTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom((new DateTime('Jan 5, 2024'))->format('Y-m-d H:i:s'));
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setRangeCreatedAt($criteriaRangeFilterTransfer);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsCollectionFilteredByCreatedAtTo(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([
            MerchantRelationshipTransfer::CREATED_AT => (new DateTime('Jan 1, 2024'))->format('Y-m-d H:i:s'),
        ]);
        $this->tester->createMerchantRelationshipBySeedData([
            MerchantRelationshipTransfer::CREATED_AT => (new DateTime('Jan 10, 2024'))->format('Y-m-d H:i:s'),
        ]);

        $criteriaRangeFilterTransfer = (new CriteriaRangeFilterTransfer())
            ->setTo((new DateTime('Jan 5, 2024'))->format('Y-m-d H:i:s'));
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setRangeCreatedAt($criteriaRangeFilterTransfer);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationshipTransfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testFiltersCollectionByOwnerCompanyBusinessUnitNameSearchConditions(): void
    {
        // Arrange
        $merchantRelationship1Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::NAME => 'abc',
        ]);
        $merchantRelationship2Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::NAME => 'bcd',
        ]);
        $merchantRelationship3Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::NAME => 'cde',
        ]);

        $merchantRelationshipSearchConditionsTransfer = (new MerchantRelationshipSearchConditionsTransfer())
            ->setOwnerCompanyBusinessUnitName('bc');
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipSearchConditions($merchantRelationshipSearchConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(2, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship1Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship2Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionDoesNotContainMerchantRelationship($merchantRelationship3Transfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testFiltersCollectionByOwnerCompanyBusinessUnitCompanyNameSearchConditions(): void
    {
        // Arrange
        $merchantRelationship1Transfer = $this->tester->createMerchantRelationshipBySeedData([], [
            CompanyTransfer::NAME => 'abc',
        ]);
        $merchantRelationship2Transfer = $this->tester->createMerchantRelationshipBySeedData([], [
            CompanyTransfer::NAME => 'bcd',
        ]);
        $merchantRelationship3Transfer = $this->tester->createMerchantRelationshipBySeedData([], [
            CompanyTransfer::NAME => 'cde',
        ]);

        $merchantRelationshipSearchConditionsTransfer = (new MerchantRelationshipSearchConditionsTransfer())
            ->setOwnerCompanyBusinessUnitCompanyName('bc');
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipSearchConditions($merchantRelationshipSearchConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(2, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship1Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship2Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionDoesNotContainMerchantRelationship($merchantRelationship3Transfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testFiltersCollectionByAssigneeCompanyBusinessUnitNameSearchConditions(): void
    {
        // Arrange
        $merchantRelationship1Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [], [
            CompanyBusinessUnitTransfer::NAME => 'abc',
        ]);
        $merchantRelationship2Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [], [
            CompanyBusinessUnitTransfer::NAME => 'bcd',
        ]);
        $merchantRelationship3Transfer = $this->tester->createMerchantRelationshipBySeedData([], [], [], [
            CompanyBusinessUnitTransfer::NAME => 'cde',
        ]);

        $merchantRelationshipSearchConditionsTransfer = (new MerchantRelationshipSearchConditionsTransfer())
            ->setAssigneeCompanyBusinessUnitName('cd');
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipSearchConditions($merchantRelationshipSearchConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(2, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship2Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship3Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionDoesNotContainMerchantRelationship($merchantRelationship1Transfer, $merchantRelationshipCollectionTransfer);
    }

    /**
     * @group search
     *
     * @return void
     */
    public function testFiltersCollectionBySeveralSearchConditions(): void
    {
        // Arrange
        $merchantRelationship1Transfer = $this->tester->createMerchantRelationshipBySeedData(
            [],
            [CompanyBusinessUnitTransfer::NAME => 'abc'],
            [CompanyTransfer::NAME => 'cde'],
            [CompanyBusinessUnitTransfer::NAME => 'efg'],
        );
        $merchantRelationship2Transfer = $this->tester->createMerchantRelationshipBySeedData(
            [],
            [CompanyBusinessUnitTransfer::NAME => 'efg'],
            [CompanyTransfer::NAME => 'abc'],
            [CompanyBusinessUnitTransfer::NAME => 'cde'],
        );
        $merchantRelationship3Transfer = $this->tester->createMerchantRelationshipBySeedData(
            [],
            [CompanyBusinessUnitTransfer::NAME => 'cde'],
            [CompanyTransfer::NAME => 'efg'],
            [CompanyBusinessUnitTransfer::NAME => 'abc'],
        );

        $merchantRelationshipSearchConditionsTransfer = (new MerchantRelationshipSearchConditionsTransfer())
            ->setOwnerCompanyBusinessUnitName('cd')
            ->setOwnerCompanyBusinessUnitCompanyName('cd')
            ->setAssigneeCompanyBusinessUnitName('cd');
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipSearchConditions($merchantRelationshipSearchConditionsTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(MerchantRelationshipCollectionTransfer::class, $merchantRelationshipCollectionTransfer);
        $this->assertCount(3, $merchantRelationshipCollectionTransfer->getMerchantRelationships());
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship1Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship2Transfer, $merchantRelationshipCollectionTransfer);
        $this->tester->assertCollectionContainsMerchantRelationship($merchantRelationship3Transfer, $merchantRelationshipCollectionTransfer);
    }
}
