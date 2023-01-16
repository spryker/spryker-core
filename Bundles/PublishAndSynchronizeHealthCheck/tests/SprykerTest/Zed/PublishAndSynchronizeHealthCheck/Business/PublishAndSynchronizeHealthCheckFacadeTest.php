<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheck\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckConditionsTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheck
 * @group Business
 * @group Facade
 * @group PublishAndSynchronizeHealthCheckFacadeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeHealthCheckFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_HEALTH_CHECK_KEY = 'ps:hc:valid_key';

    /**
     * @var string
     */
    public const TEST_HEALTH_CHECK_INVALID_KEY = 'ps:hc:invalid_key';

    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateOrUpdatePublishAndSynchronizeHealthCheckEntityCreatesEntity(): void
    {
        // Arrange
        /** @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacade $publishAndSynchronizeHealthCheckFacade */
        $publishAndSynchronizeHealthCheckFacade = $this->getFacade();

        // Act
        $publishAndSynchronizeHealthCheckTransfer = $publishAndSynchronizeHealthCheckFacade->savePublishAndSynchronizeHealthCheckEntity();

        // Assert
        $this->assertNotNull($publishAndSynchronizeHealthCheckTransfer->getUpdatedAt());
    }

    /**
     * @return void
     */
    public function testGetPublishAndSynchronizeHealthCheckCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->createPublishAndSynchronizeHealthCheck();
        $publishAndSynchronizeHealthCheckConditionsTransfer = (new PublishAndSynchronizeHealthCheckConditionsTransfer())
            ->addIdPublishAndSynchronizeHealthCheck(0);
        $publishAndSynchronizeHealthCheckCriteriaTransfer = (new PublishAndSynchronizeHealthCheckCriteriaTransfer())
            ->setPublishAndSynchronizeHealthCheckConditions($publishAndSynchronizeHealthCheckConditionsTransfer);

        // Act
        $publishAndSynchronizeHealthCheckCollectionTransfer = $this->tester->getFacade()->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer);

        // Assert
        $this->assertCount(0, $publishAndSynchronizeHealthCheckCollectionTransfer->getPublishAndSynchronizeHealthChecks());
    }

    /**
     * @return void
     */
    public function testGetPublishAndSynchronizeHealthCheckCollectionWithOnePublishAndSynchronizeHealthCheckWhilePublishAndSynchronizeHealthCheckCriteriaMatched(): void
    {
        // Arrange
        $this->tester->createPublishAndSynchronizeHealthCheck();
        $publishAndSynchronizeHealthCheckTransfer = $this->tester->createPublishAndSynchronizeHealthCheck();

        $publishAndSynchronizeHealthCheckConditionsTransfer = (new PublishAndSynchronizeHealthCheckConditionsTransfer())
            ->addIdPublishAndSynchronizeHealthCheck($publishAndSynchronizeHealthCheckTransfer->getIdPublishAndSynchronizeHealthCheck());
        $publishAndSynchronizeHealthCheckCriteriaTransfer = (new PublishAndSynchronizeHealthCheckCriteriaTransfer())
            ->setPublishAndSynchronizeHealthCheckConditions($publishAndSynchronizeHealthCheckConditionsTransfer);

        // Act
        $publishAndSynchronizeHealthCheckCollectionTransfer = $this->getFacade()
            ->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer);

        // Assert
        $this->assertCount(1, $publishAndSynchronizeHealthCheckCollectionTransfer->getPublishAndSynchronizeHealthChecks());
        $this->assertEquals(
            $publishAndSynchronizeHealthCheckTransfer->getIdPublishAndSynchronizeHealthCheck(),
            $publishAndSynchronizeHealthCheckCollectionTransfer->getPublishAndSynchronizeHealthChecks()[0]->getIdPublishAndSynchronizeHealthCheck(),
        );
    }

    /**
     * @return void
     */
    public function testGetPublishAndSynchronizeHealthCheckCollectionWithFivePublishAndSynchronizeHealthChecksWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 4; $i++) {
            $this->tester->createPublishAndSynchronizeHealthCheck();
        }

        $publishAndSynchronizeHealthCheckCriteriaTransfer = (new PublishAndSynchronizeHealthCheckCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(2),
            );

        // Act
        $assetCollectionTransfer = $this->getFacade()
            ->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer);

        // Assert
        $this->assertCount(2, $assetCollectionTransfer->getPublishAndSynchronizeHealthChecks());
        $this->assertSame(4, $assetCollectionTransfer->getPagination()->getNbResults());
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface
     */
    protected function getFacade(): PublishAndSynchronizeHealthCheckFacadeInterface
    {
        /** @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface $publishAndSynchronizeHealthCheckFacade */
        $publishAndSynchronizeHealthCheckFacade = $this->tester->getFacade();

        return $publishAndSynchronizeHealthCheckFacade;
    }
}
