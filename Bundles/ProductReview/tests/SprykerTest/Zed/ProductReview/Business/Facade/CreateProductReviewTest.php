<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Propel\Runtime\Exception\PropelException;
use Spryker\Client\ProductReview\ProductReviewClientInterface;
use Spryker\Shared\ProductReview\Exception\RatingOutOfRangeException;
use Spryker\Zed\ProductReview\ProductReviewDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group CreateProductReviewTest
 * Add your own group annotations below this line
 */
class CreateProductReviewTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateProductReviewPersistsToDatabase(): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertNotNull($actualProductReviewTransfer, 'Product review should have been created and found in database.');
    }

    /**
     * @dataProvider validStatusDataProvider
     *
     * @param string|null $inputStatus
     *
     * @return void
     */
    public function testCreateProductReviewIsCreatedAlwaysWithProvidedValidStatus(?string $inputStatus): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview([
            ProductReviewTransfer::STATUS => $inputStatus,
        ]);

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertSame($inputStatus, $actualProductReviewTransfer->getStatus(), 'Product review should have been created with expected status.');
    }

    /**
     * @dataProvider emptyStatusDataProvider
     *
     * @param string|null $inputStatus
     *
     * @return void
     */
    public function testCreateProductReviewIsCreatedAlwaysWithPendingStatusWhenEmptyStatusProvided(?string $inputStatus): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview([
            ProductReviewTransfer::STATUS => $inputStatus,
        ]);

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertSame(SpyProductReviewTableMap::COL_STATUS_PENDING, $actualProductReviewTransfer->getStatus(), 'Product review should have been created with expected status.');
    }

    /**
     * @return void
     */
    public function testCreateProductReviewThrowsExceptionWhenInvalidStatusProvided(): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview([
            ProductReviewTransfer::STATUS => 'some_status',
        ]);

        // Assert
        $this->expectException(PropelException::class);

        // Act
        $this->tester->getFacade()->createProductReview($productReviewTransfer);
    }

    /**
     * @return array
     */
    public function validStatusDataProvider(): array
    {
        return [
            'pending status' => [SpyProductReviewTableMap::COL_STATUS_PENDING],
            'approved status' => [SpyProductReviewTableMap::COL_STATUS_APPROVED],
            'rejected status' => [SpyProductReviewTableMap::COL_STATUS_REJECTED],
        ];
    }

    /**
     * @return array
     */
    public function emptyStatusDataProvider(): array
    {
        return [
            'empty string' => [''],
            'status not defined' => [null],
        ];
    }

    /**
     * @return void
     */
    public function testCreateProductReviewReturnsUpdatedTransfer(): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();

        // Act
        $actualProductReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $expectedProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertSame(
            $this->tester->removeProductReviewDateFields($expectedProductReviewTransfer->toArray()),
            $this->tester->removeProductReviewDateFields($actualProductReviewTransfer->toArray()),
            'Updated product review should have been returned.',
        );
    }

    /**
     * @return void
     */
    public function testCreateProductReviewThrowsExceptionWhenRatingExceedsRange(): void
    {
        // Arrange
        $productReviewClientMock = $this->getProductReviewClientMock();
        $this->tester->setDependency(ProductReviewDependencyProvider::CLIENT_PRODUCT_REVIEW, $productReviewClientMock);

        $productReviewTransfer = $this->tester->haveProductReview([
            ProductReviewTransfer::RATING => $productReviewClientMock->getMaximumRating() + 1,
        ]);

        // Assert
        $this->expectException(RatingOutOfRangeException::class);

        // Act
        $this->tester->getFacade()->createProductReview($productReviewTransfer);
    }

    /**
     * @return \Spryker\Client\ProductReview\ProductReviewClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductReviewClientMock(): ProductReviewClientInterface
    {
        $productReviewClientInterfaceMock = $this->getMockBuilder(ProductReviewClientInterface::class)->getMock();

        return $productReviewClientInterfaceMock;
    }
}
