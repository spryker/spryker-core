<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestSearchConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group GetMerchantRelationRequestCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantRelationRequestCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'FAKE_UUID';

    /**
     * @var int
     */
    protected const FAKE_ID = 123456;

    /**
     * @var \SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester
     */
    protected MerchantRelationRequestBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureMerchantRelationRequestTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByUuid(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer1->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
        $this->assertSame(
            $merchantRelationRequestTransfer1->getUuid(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByUuids(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1, $merchantRelationRequestTransfer2] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer1->getUuid())
            ->addUuid($merchantRelationRequestTransfer2->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenFilterByFakeUuid(): void
    {
        // Arrange
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid(static::FAKE_UUID);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsById(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchantRelationRequest($merchantRelationRequestTransfer1->getIdMerchantRelationRequest());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
        $this->assertSame(
            $merchantRelationRequestTransfer1->getUuid(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByIds(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1, $merchantRelationRequestTransfer2] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchantRelationRequest($merchantRelationRequestTransfer1->getIdMerchantRelationRequest())
            ->addIdMerchantRelationRequest($merchantRelationRequestTransfer2->getIdMerchantRelationRequest());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenFilterByFakeId(): void
    {
        // Arrange
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchantRelationRequest(static::FAKE_ID);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByStatuses(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('approved');
        $this->tester->createStatusSpecificRequest('rejected');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addStatus('approved')
            ->addStatus('rejected');

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByIdCompany(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompany($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnit()->getFkCompany());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByCompanyIds(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompany($merchantRelationRequestTransfer1->getOwnerCompanyBusinessUnit()->getFkCompany());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByIdMerchant(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($merchantRelationRequestTransfer->getMerchant()->getIdMerchant());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByMerchantIds(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($merchantRelationRequestTransfer1->getMerchant()->getIdMerchant());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByIdCompanyUser(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompanyUser($merchantRelationRequestTransfer->getCompanyUser()->getIdCompanyUser());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByCompanyUserIds(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant();

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompanyUser($merchantRelationRequestTransfer1->getCompanyUser()->getIdCompanyUser());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByIdOwnerCompanyBusinessUnit(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdOwnerCompanyBusinessUnit($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByCreatedFrom(): void
    {
        // Arrange
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant(
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-01-30 00:00:00'],
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-02-02 00:00:00'],
        );

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->setRangeCreatedAt((new CriteriaRangeFilterTransfer())->setFrom('2024-02-01 00:00:00'));

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByCreatedTo(): void
    {
        // Arrange
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant(
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-01-30 00:00:00'],
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-02-02 00:00:00'],
        );

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->setRangeCreatedAt((new CriteriaRangeFilterTransfer())->setTo('2024-02-01 00:00:00'));

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldFilterMerchantRelationRequestsByCreatedFromAndCreatedTo(): void
    {
        // Arrange
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant(
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-01-30 00:00:00'],
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-02-02 00:00:00'],
        );
        $this->tester->createTwoMerchantRelationRequestsToSameMerchant(
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-01-29 00:00:00'],
            [MerchantRelationRequestTransfer::CREATED_AT => '2024-02-03 00:00:00'],
        );

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->setRangeCreatedAt((new CriteriaRangeFilterTransfer())
                ->setFrom('2024-01-29 00:00:00')
                ->setTo('2024-02-02 00:00:00'));

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfRequestsWithLimitAndOffset(): void
    {
        // Arrange
        $merchantRelationRequestTransfers = [
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
        ];

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(3)
            ->setLimit(2);
        $sortTransfer = (new SortTransfer())
            ->setField(MerchantRelationRequestTransfer::ID_MERCHANT_RELATION_REQUEST)
            ->setIsAscending(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->addSort($sortTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
        $this->assertEqualsCanonicalizing(
            [
                $merchantRelationRequestTransfers[3]->getUuid(),
                $merchantRelationRequestTransfers[4]->getUuid(),
            ],
            [
                $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getUuid(),
                $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(1)->getUuid(),
            ],
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfRequestsWithPageAndMaxPerPage(): void
    {
        // Arrange
        $merchantRelationRequestTransfers = [
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
            $this->tester->createStatusSpecificRequest('pending'),
        ];

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);
        $sortTransfer = (new SortTransfer())
            ->setField(MerchantRelationRequestTransfer::ID_MERCHANT_RELATION_REQUEST)
            ->setIsAscending(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->addSort($sortTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
        $this->assertEqualsCanonicalizing(
            [
                $merchantRelationRequestTransfers[2]->getUuid(),
                $merchantRelationRequestTransfers[3]->getUuid(),
            ],
            [
                $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getUuid(),
                $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(1)->getUuid(),
            ],
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfRequestsWithEmptyPagination(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setPagination(new PaginationTransfer());

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testShouldReturnsRequestsSortedByFieldDesc(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createStatusSpecificRequest('rejected');
        $this->tester->createStatusSpecificRequest('approved');

        $sortTransfer = (new SortTransfer())
            ->setField(MerchantRelationRequestTransfer::STATUS)
            ->setIsAscending(false);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $merchantRelationRequests = $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests();
        $this->assertCount(3, $merchantRelationRequests);
        $this->assertSame('rejected', $merchantRelationRequests->offsetGet(0)->getStatus());
        $this->assertSame('pending', $merchantRelationRequests->offsetGet(1)->getStatus());
        $this->assertSame('approved', $merchantRelationRequests->offsetGet(2)->getStatus());
    }

    /**
     * @return void
     */
    public function testShouldContainCompanyUserInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationRequestTransfer->getCompanyUser()->getIdCompanyUser(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getCompanyUser()->getIdCompanyUser(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainMerchantInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationRequestTransfer->getMerchant()->getIdMerchant(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getMerchant()->getIdMerchant(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainOwnerCompanyBusinessUnitInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainCompanyInOwnerCompanyBusinessUnitRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnit()->getFkCompany(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getOwnerCompanyBusinessUnit()->getCompany()->getIdCompany(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainAssigneeCompanyBusinessUnitsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithAssigneeCompanyBusinessUnitRelations(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            $merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->count(),
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getAssigneeCompanyBusinessUnits()->count(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainEmptyAssigneeCompanyBusinessUnitsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->deleteAll();

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithAssigneeCompanyBusinessUnitRelations(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getAssigneeCompanyBusinessUnits(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotContainAssigneeCompanyBusinessUnitsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithAssigneeCompanyBusinessUnitRelations(false);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getAssigneeCompanyBusinessUnits(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainMerchantRelationshipsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createMerchantRelationship($merchantRelationRequestTransfer->getUuid());
        $this->tester->createMerchantRelationship($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithMerchantRelationshipRelations(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(
            2,
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getMerchantRelationships()->count(),
        );
    }

    /**
     * @return void
     */
    public function testShouldContainEmptyMerchantRelationshipsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithMerchantRelationshipRelations(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getMerchantRelationships(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotContainMerchantRelationshipsInRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $this->tester->createMerchantRelationship($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithMerchantRelationshipRelations(false);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->getIterator()->current()->getMerchantRelationships(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExecuteMerchantRelationRequestExpanderPluginStack(): void
    {
        // Assert
        $merchantRelationRequestExpanderPluginMock = $this->createMerchantRelationRequestExpanderPluginMock();

        // Arrange
        $this->tester->setDependency(
            MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER,
            [$merchantRelationRequestExpanderPluginMock],
        );

        $this->tester->createStatusSpecificRequest('pending');

        // Act
        $this->tester->getFacade()->getMerchantRelationRequestCollection(new MerchantRelationRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testShouldExecuteMerchantRelationRequestExpanderPluginStackWithConditions(): void
    {
        // Assert
        $merchantRelationRequestExpanderPluginMock = $this->createMerchantRelationRequestExpanderPluginMock();

        // Arrange
        $this->tester->setDependency(
            MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER,
            [$merchantRelationRequestExpanderPluginMock],
        );

        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid())
            ->setWithAssigneeCompanyBusinessUnitRelations(false);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $this->tester->getFacade()->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnMerchantRelationRequestWithCustomerInsideCompanyUser(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');
        $customerFromRequest = $merchantRelationRequestTransfer->getCompanyUser()->getCustomer();

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuid());

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $customerTransfer = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests()
            ->getIterator()
            ->current()
            ->getCompanyUser()
            ->getCustomer();

        // Assert
        $this->assertSame($customerFromRequest->getIdCustomer(), $customerTransfer->getIdCustomer());
        $this->assertSame($customerFromRequest->getEmail(), $customerTransfer->getEmail());
        $this->assertSame($customerFromRequest->getFirstName(), $customerTransfer->getFirstName());
        $this->assertSame($customerFromRequest->getLastName(), $customerTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testShouldSearchByOwnerCompanyBusinessUnitName(): void
    {
        // Assert
        $this->tester->createStatusSpecificRequest('approved', [], [
            CompanyBusinessUnitTransfer::NAME => 'abc',
        ]);
        $this->tester->createStatusSpecificRequest('pending', [], [
            CompanyBusinessUnitTransfer::NAME => 'bcd',
        ]);
        $this->tester->createStatusSpecificRequest('canceled', [], [
            CompanyBusinessUnitTransfer::NAME => 'cde',
        ]);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestSearchConditions(
                (new MerchantRelationRequestSearchConditionsTransfer())
                    ->setOwnerCompanyBusinessUnitName('bc'),
            );

        // Act
        $merchantRelationRequestTransfers = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        // Assert
        $this->assertCount(2, $merchantRelationRequestTransfers);
        $expectedCompanyBusinessUnitNames = ['abc', 'bcd'];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $this->assertContains(
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getName(),
                $expectedCompanyBusinessUnitNames,
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldSearchByOwnerCompanyBusinessUnitCompanyName(): void
    {
        // Assert
        $this->tester->createStatusSpecificRequest('approved', [
            CompanyTransfer::NAME => 'abc',
        ]);
        $this->tester->createStatusSpecificRequest('pending', [
            CompanyTransfer::NAME => 'bcd',
        ]);
        $this->tester->createStatusSpecificRequest('canceled', [
            CompanyTransfer::NAME => 'cde',
        ]);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestSearchConditions(
                (new MerchantRelationRequestSearchConditionsTransfer())
                    ->setOwnerCompanyBusinessUnitCompanyName('bc'),
            );

        // Act
        $merchantRelationRequestTransfers = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        // Assert
        $this->assertCount(2, $merchantRelationRequestTransfers);
        $expectedCompanyBusinessUnitNames = ['abc', 'bcd'];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $this->assertContains(
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail()->getName(),
                $expectedCompanyBusinessUnitNames,
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldSearchByAssigneeCompanyBusinessUnitName(): void
    {
        // Assert
        $this->tester->createStatusSpecificRequest('approved', [], [], [
            CompanyBusinessUnitTransfer::NAME => 'abc',
        ]);
        $this->tester->createStatusSpecificRequest('pending', [], [], [
            CompanyBusinessUnitTransfer::NAME => 'bcd',
        ]);
        $this->tester->createStatusSpecificRequest('canceled', [], [], [
            CompanyBusinessUnitTransfer::NAME => 'cde',
        ]);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())->setWithAssigneeCompanyBusinessUnitRelations(true),
            )
            ->setMerchantRelationRequestSearchConditions(
                (new MerchantRelationRequestSearchConditionsTransfer())
                    ->setAssigneeCompanyBusinessUnitName('bc'),
            );

        // Act
        $merchantRelationRequestTransfers = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        // Assert
        $this->assertCount(2, $merchantRelationRequestTransfers);
        $expectedCompanyBusinessUnitNames = ['abc', 'bcd'];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $this->assertContains(
                    $companyBusinessUnitTransfer->getName(),
                    $expectedCompanyBusinessUnitNames,
                );
            }
        }
    }

    /**
     * @return void
     */
    public function testShouldSearchByFewFields(): void
    {
        // Assert
        $this->tester->createStatusSpecificRequest(
            'approved',
            [CompanyTransfer::NAME => 'abc'],
            [CompanyBusinessUnitTransfer::NAME => 'cde'],
            [CompanyBusinessUnitTransfer::NAME => 'def'],
        );
        $this->tester->createStatusSpecificRequest(
            'pending',
            [CompanyTransfer::NAME => 'cde'],
            [CompanyBusinessUnitTransfer::NAME => 'abc'],
            [CompanyBusinessUnitTransfer::NAME => 'def'],
        );
        $this->tester->createStatusSpecificRequest(
            'canceled',
            [CompanyTransfer::NAME => 'cde'],
            [CompanyBusinessUnitTransfer::NAME => 'efg'],
            [CompanyBusinessUnitTransfer::NAME => 'def'],
        );
        $this->tester->createStatusSpecificRequest(
            'pending',
            [CompanyTransfer::NAME => 'cde'],
            [CompanyBusinessUnitTransfer::NAME => 'def'],
            [CompanyBusinessUnitTransfer::NAME => 'abc'],
        );
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestSearchConditions(
                (new MerchantRelationRequestSearchConditionsTransfer())
                    ->setOwnerCompanyBusinessUnitName('bc')
                    ->setOwnerCompanyBusinessUnitCompanyName('bc')
                    ->setAssigneeCompanyBusinessUnitName('bc'),
            );

        // Act
        $merchantRelationRequestTransfers = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        // Assert
        $this->assertCount(3, $merchantRelationRequestTransfers);
        $expectedCompanyBusinessUnitNames = ['cde', 'abc', 'def'];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $this->assertContains(
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getName(),
                $expectedCompanyBusinessUnitNames,
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldSearchByOwnerCompanyBusinessUnitNameWithMerchantIdsFilter(): void
    {
        // Arrange
        [$merchantRelationRequestTransfer1] = $this->tester->createTwoMerchantRelationRequestsToSameMerchant([], [], [
            CompanyBusinessUnitTransfer::NAME => 'abc',
        ]);

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($merchantRelationRequestTransfer1->getMerchant()->getIdMerchant());
        $merchantRelationRequestSearchConditionsTransfer = (new MerchantRelationRequestSearchConditionsTransfer())
            ->setOwnerCompanyBusinessUnitName('bc');

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer)
            ->setMerchantRelationRequestSearchConditions($merchantRelationRequestSearchConditionsTransfer);

        // Act
        $merchantRelationRequestTransfers = $this->tester->getFacade()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        // Assert
        $this->assertCount(1, $merchantRelationRequestTransfers);
        $this->assertSame('abc', $merchantRelationRequestTransfers->offsetGet(0)->getOwnerCompanyBusinessUnitOrFail()->getName());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMerchantRelationRequestExpanderPluginMock(): MerchantRelationRequestExpanderPluginInterface
    {
        $merchantRelationRequestExpanderPluginMock = $this
            ->getMockBuilder(MerchantRelationRequestExpanderPluginInterface::class)
            ->getMock();

        $merchantRelationRequestExpanderPluginMock
            ->expects($this->once())
            ->method('expand');

        return $merchantRelationRequestExpanderPluginMock;
    }
}
