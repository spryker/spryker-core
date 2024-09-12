<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntitySegmentConditionsTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\AclEntity\AclEntityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntity
 * @group Business
 * @group Facade
 * @group GetAclEntitySegmentCollectionTest
 * Add your own group annotations below this line
 */
class GetAclEntitySegmentCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TARGET_ENTITY = 'Orm\Zed\Merchant\Persistence\SpyMerchant';

    /**
     * @var \SprykerTest\Zed\AclEntity\AclEntityBusinessTester
     */
    protected AclEntityBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureAclEntitySegmentDatabaseIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntitySegmentByIdAclEntitySegment(): void
    {
        // Arrange
        $aclEntitySegmentTransfer = $this->haveAclEntitySegmentWithMerchant();

        $aclEntitySegmentConditions = (new AclEntitySegmentConditionsTransfer())
            ->addIdAclEntitySegment($aclEntitySegmentTransfer->getIdAclEntitySegmentOrFail());
        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())
            ->setAclEntitySegmentConditions($aclEntitySegmentConditions);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $this->assertSameAclEntitySegmentTransfer(
            $aclEntitySegmentTransfer,
            $aclEntitySegmentCollectionTransfer->getAclEntitySegments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntitySegmentByName(): void
    {
        // Arrange
        $aclEntitySegmentTransfer = $this->haveAclEntitySegmentWithMerchant();

        $aclEntitySegmentConditions = (new AclEntitySegmentConditionsTransfer())
            ->addName($aclEntitySegmentTransfer->getNameOrFail());
        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())
            ->setAclEntitySegmentConditions($aclEntitySegmentConditions);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $this->assertSameAclEntitySegmentTransfer(
            $aclEntitySegmentTransfer,
            $aclEntitySegmentCollectionTransfer->getAclEntitySegments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectAclEntitySegmentByReference(): void
    {
        // Arrange
        $aclEntitySegmentTransfer = $this->haveAclEntitySegmentWithMerchant();

        $aclEntitySegmentConditions = (new AclEntitySegmentConditionsTransfer())
            ->addReference($aclEntitySegmentTransfer->getReferenceOrFail());
        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())
            ->setAclEntitySegmentConditions($aclEntitySegmentConditions);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(1, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $this->assertSameAclEntitySegmentTransfer(
            $aclEntitySegmentTransfer,
            $aclEntitySegmentCollectionTransfer->getAclEntitySegments()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsAclEntitySegmentsPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $this->assertNotNull($aclEntitySegmentCollectionTransfer->getPagination());
        $this->assertSame(4, $aclEntitySegmentCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntitySegmentsPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();
        $this->haveAclEntitySegmentWithMerchant();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(2, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $this->assertNotNull($aclEntitySegmentCollectionTransfer->getPagination());
        $this->assertSame(4, $aclEntitySegmentCollectionTransfer->getPaginationOrFail()->getNbResults());

        $paginationTransfer = $aclEntitySegmentCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(4, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(2, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(2, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntitySegmentsSortedByNameFieldDesc(): void
    {
        // Arrange
        $this->haveAclEntitySegmentWithMerchant('abc');
        $this->haveAclEntitySegmentWithMerchant('def');
        $this->haveAclEntitySegmentWithMerchant('ghi');

        $sortTransfer = (new SortTransfer())
            ->setField(AclEntitySegmentTransfer::NAME)
            ->setIsAscending(false);

        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(3, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $aclEntitySegmentCollectionIterator = $aclEntitySegmentCollectionTransfer->getAclEntitySegments()->getIterator();
        $this->assertSame('ghi', $aclEntitySegmentCollectionIterator->offsetGet(0)->getNameOrFail());
        $this->assertSame('def', $aclEntitySegmentCollectionIterator->offsetGet(1)->getNameOrFail());
        $this->assertSame('abc', $aclEntitySegmentCollectionIterator->offsetGet(2)->getNameOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsAclEntitySegmentsSortedByNameFieldAsc(): void
    {
        // Arrange
        $this->haveAclEntitySegmentWithMerchant('abc');
        $this->haveAclEntitySegmentWithMerchant('def');
        $this->haveAclEntitySegmentWithMerchant('ghi');

        $sortTransfer = (new SortTransfer())
            ->setField(AclEntitySegmentTransfer::NAME)
            ->setIsAscending(true);

        $aclEntitySegmentCriteriaTransfer = (new AclEntitySegmentCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $aclEntitySegmentCollectionTransfer = $this->tester->getFacade()->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);

        // Assert
        $this->assertCount(3, $aclEntitySegmentCollectionTransfer->getAclEntitySegments());
        $aclEntitySegmentCollectionIterator = $aclEntitySegmentCollectionTransfer->getAclEntitySegments()->getIterator();
        $this->assertSame('abc', $aclEntitySegmentCollectionIterator->offsetGet(0)->getNameOrFail());
        $this->assertSame('def', $aclEntitySegmentCollectionIterator->offsetGet(1)->getNameOrFail());
        $this->assertSame('ghi', $aclEntitySegmentCollectionIterator->offsetGet(2)->getNameOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $expectedAclEntitySegmentTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $actualAclEntitySegmentTransfer
     *
     * @return void
     */
    protected function assertSameAclEntitySegmentTransfer(
        AclEntitySegmentTransfer $expectedAclEntitySegmentTransfer,
        AclEntitySegmentTransfer $actualAclEntitySegmentTransfer
    ): void {
        $this->assertSame($expectedAclEntitySegmentTransfer->getIdAclEntitySegment(), $actualAclEntitySegmentTransfer->getIdAclEntitySegment());
        $this->assertSame($expectedAclEntitySegmentTransfer->getName(), $actualAclEntitySegmentTransfer->getName());
        $this->assertSame($expectedAclEntitySegmentTransfer->getReference(), $actualAclEntitySegmentTransfer->getReference());
    }

    /**
     * @param string|null $name
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    protected function haveAclEntitySegmentWithMerchant(
        ?string $name = null
    ): AclEntitySegmentTransfer {
        $merchantTransfer = $this->tester->haveMerchant();

        $seed = [
            AclEntitySegmentRequestTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
            AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchant()],
        ];

        if ($name) {
            $seed[AclEntitySegmentRequestTransfer::NAME] = $name;
        }

        return $this->tester->haveAclEntitySegment($seed);
    }
}
