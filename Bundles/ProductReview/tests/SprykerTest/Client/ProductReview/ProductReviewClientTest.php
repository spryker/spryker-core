<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReview;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface;
use Spryker\Client\ProductReview\ProductReviewClient;
use Spryker\Client\ProductReview\ProductReviewClientInterface;
use Spryker\Client\ProductReview\ProductReviewDependencyProvider;

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
     * @retyrn void
     *
     * @return void
     */
    public function testExpandProductViewBatchWithProductReviewData(): void
    {
        $result = $this->createClinetSearchMockResponse();

        $this->setSearchReturn($result);

        $productViews = $this->createProductViews();
        $prductViewsExpended = $this->createProductViewSearchClient()
            ->expandProductViewBatchWithProductReviewData($productViews, $this->createBulkProductReviewSearchRequestTransfer());

        foreach ($this->assertTestData() as $productId => $testData) {
            $this->assertEquals($prductViewsExpended[$productId]->getIdProductAbstract(), $productId);
            $this->assertEquals($prductViewsExpended[$productId]->getRating()->getAverageRating(), $testData['averageRating']);
        }
    }

    /**
     * @return int[][][][]
     */
    protected function createClinetSearchMockResponse(): array
    {
        return [
            'productAggregation' => [
                1 => [
                    'ratingAggregation' => [
                        5 => 3,
                        2 => 1,
                    ],
                ],
                2 => [
                    'ratingAggregation' => [
                        5 => 3,
                        1 => 10,
                    ],
                ],
                3 => [
                    'ratingAggregation' => [
                        5 => 130,
                        4 => 33,
                        3 => 21,
                        2 => 10,
                        1 => 5,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \float[][]
     */
    protected function assertTestData(): array
    {
        return [
            1 => [
                'averageRating' => 4.3,
            ],
            2 => [
                'averageRating' => 1.9,
            ],
            3 => [
                'averageRating' => 4.4,
            ],

        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function createProductViews()
    {
        $prodcutView = new ProductViewTransfer();
        $prodcutView->setIdProductAbstract(1);

        $prodcutView2 = new ProductViewTransfer();
        $prodcutView2->setIdProductAbstract(2);

        $prodcutView3 = new ProductViewTransfer();
        $prodcutView3->setIdProductAbstract(3);

        return [
            1 => $prodcutView,
            2 => $prodcutView2,
            3 => $prodcutView3,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    protected function createBulkProductReviewSearchRequestTransfer()
    {
        $bulkProductReviewSearchRequestTransfer = new BulkProductReviewSearchRequestTransfer();
        $bulkProductReviewSearchRequestTransfer->setProductAbstractIds([
            1, 2, 3,
        ]);
        $bulkProductReviewSearchRequestTransfer->setFilter(new FilterTransfer());

        return $bulkProductReviewSearchRequestTransfer;
    }

    /**
     * @param array $returnedContent
     *
     * @return void
     */
    protected function setSearchReturn(array $returnedContent): void
    {
        $contentToStorageBridge = $this->getMockBuilder(ProductReviewToSearchInterface::class)->getMock();
        $contentToStorageBridge->method('search')->willReturn($returnedContent);
        $contentToStorageBridge->method('expandQuery')->willReturn($this->cerateQueryMock());

        $this->tester->setDependency(ProductReviewDependencyProvider::CLIENT_SEARCH, $contentToStorageBridge);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function cerateQueryMock()
    {
        return $this->createMock(QueryInterface::class);
    }

    /**
     * @return \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected function createProductViewSearchClient(): ProductReviewClientInterface
    {
        return new ProductReviewClient();
    }
}
