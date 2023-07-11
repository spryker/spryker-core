<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewSearch\Plugin\Search;

use Codeception\Test\Unit;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Client\ProductReviewSearch\Plugin\Search\FilterByIdProductReviewQueryExpanderPlugin;
use SprykerTest\Client\ProductReviewSearch\ProductReviewSearchClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewSearch
 * @group Plugin
 * @group Search
 * @group FilterByIdProductReviewQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class FilterByIdProductReviewQueryExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const QUERY_PARAM_FILTER = 'filter';

    /**
     * @uses \Spryker\Client\ProductReviewSearch\Plugin\Search\FilterByIdProductReviewQueryExpanderPlugin::REQUEST_PARAM_ID_PRODUCT_REVIEW
     *
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT_REVIEW = ProductReviewTransfer::ID_PRODUCT_REVIEW;

    /**
     * @var string
     */
    protected const KEY_BOOL = 'bool';

    /**
     * @var string
     */
    protected const KEY_TERM = 'term';

    /**
     * @var string
     */
    protected const KEY_ID = '_id';

    /**
     * @var string
     */
    protected const KEY_VALUE = 'value';

    /**
     * @var string
     */
    protected const KEY_FILTER = 'filter';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_REVIEW_ID = 777;

    /**
     * @var string
     */
    protected const EXPECTED_FILTER_VALUE = 'product_review:777';

    /**
     * @var \SprykerTest\Client\ProductReviewSearch\ProductReviewSearchClientTester
     */
    protected ProductReviewSearchClientTester $tester;

    /**
     * @return void
     */
    public function testExpandQueryShouldNotAddFilterWhenIdProductReviewParamIsNotProvided(): void
    {
        // Arrange
        $query = $this->tester->createQueryMock(new BoolQuery());

        // Act
        $query = (new FilterByIdProductReviewQueryExpanderPlugin())->expandQuery($query);

        // Assert
        /** @var \Elastica\Query\BoolQuery $resultBoolQuery */
        $resultBoolQuery = $query->getSearchQuery()->getQuery();
        $this->assertFalse($resultBoolQuery->hasParam(static::QUERY_PARAM_FILTER));
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldAddFilterWhenIdProductReviewParamIsProvided(): void
    {
        // Arrange
        $query = $this->tester->createQueryMock(new BoolQuery());
        $requestParameters = [static::REQUEST_PARAM_ID_PRODUCT_REVIEW => static::TEST_PRODUCT_REVIEW_ID];

        // Act
        $query = (new FilterByIdProductReviewQueryExpanderPlugin())->expandQuery($query, $requestParameters);

        // Assert
        /** @var \Elastica\Query\BoolQuery $resultBoolQuery */
        $resultBoolQuery = $query->getSearchQuery()->getQuery();
        $this->assertTrue($resultBoolQuery->hasParam(static::QUERY_PARAM_FILTER));

        /** @var \Elastica\Query\Terms $term */
        $term = $resultBoolQuery->getParam(static::QUERY_PARAM_FILTER)[0];
        $termData = $term->toArray();
        $this->assertArrayHasKey(static::KEY_ID, $termData[static::KEY_BOOL][static::KEY_FILTER][0][static::KEY_TERM]);
        $this->assertSame(
            static::EXPECTED_FILTER_VALUE,
            $termData[static::KEY_BOOL][static::KEY_FILTER][0][static::KEY_TERM][static::KEY_ID][static::KEY_VALUE],
        );
    }
}
