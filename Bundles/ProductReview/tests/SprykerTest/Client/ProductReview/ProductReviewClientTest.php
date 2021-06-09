<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReview;

use Codeception\Test\Unit;
use Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface;
use Spryker\Client\ProductReview\ProductReviewDependencyProvider;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductReview
 * @group ProductReviewClientTest
 * Add your own group annotations below this line
 */
class ProductReviewClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductReview\ProductReviewClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductViewBatchWithProductReviewData(): void
    {
        // Arrange
        $this->mockSearchResult($this->tester->createClinetSearchMockResponse());
        $productViews = $this->tester->createProductViews();

        // Act
        $prductViewsExpended = $this->tester->getClient()
            ->expandProductViewBatchWithProductReviewData($productViews, $this->tester->createBulkProductReviewSearchRequestTransfer());

        // Assert
        foreach ($this->tester->expectedResultData() as $productId => $testData) {
            $this->assertEquals($prductViewsExpended[$productId]->getIdProductAbstract(), $productId);
            $this->assertEquals($prductViewsExpended[$productId]->getRating()->getAverageRating(), $testData['averageRating']);
        }
    }

    /**
     * @param array $returnedContent
     *
     * @return void
     */
    protected function mockSearchResult(array $returnedContent): void
    {
        $contentToStorageBridge = $this->getMockBuilder(ProductReviewToSearchInterface::class)->getMock();
        $contentToStorageBridge->method('search')->willReturn($returnedContent);
        $contentToStorageBridge->method('expandQuery')->willReturn($this->cerateQueryMock());

        $this->tester->setDependency(ProductReviewDependencyProvider::CLIENT_SEARCH, $contentToStorageBridge);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function cerateQueryMock(): QueryInterface
    {
        return $this->createMock(QueryInterface::class);
    }
}
