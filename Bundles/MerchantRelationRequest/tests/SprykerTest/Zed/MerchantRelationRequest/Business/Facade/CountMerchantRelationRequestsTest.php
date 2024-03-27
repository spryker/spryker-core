<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group CountMerchantRelationRequestsTest
 * Add your own group annotations below this line
 */
class CountMerchantRelationRequestsTest extends Unit
{
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
    public function testShouldFiltersByMerchantRelationRequestId(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchantRelationRequest($merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByUuid(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($merchantRelationRequestTransfer->getUuidOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByStatus(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('approved');
        $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addStatus('pending');
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByCompanyId(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompany($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getFkCompany());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByMerchantId(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByCompanyUserId(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompanyUser($merchantRelationRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }

    /**
     * @return void
     */
    public function testShouldFiltersByOwnerCompanyBusinessUnitId(): void
    {
        // Arrange
        $this->tester->createStatusSpecificRequest('pending');
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('pending');

        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdOwnerCompanyBusinessUnit($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        // Act
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
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
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
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
        $merchantRelationRequestsCount = $this->tester->getFacade()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);

        // Assert
        $this->assertSame(1, $merchantRelationRequestsCount);
    }
}
