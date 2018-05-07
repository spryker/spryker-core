<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class ProductReviewsResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    const NAME = 'productReviews';

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function formatSearchResult(ResultSet $searchResult, array $requestParameters = [])
    {
        $productReviews = [];
        foreach ($searchResult->getResults() as $document) {
            $productReviews[] = $this->mapProductReviewDocument($document->getSource()[ProductReviewIndexMap::SEARCH_RESULT_DATA]);
        }

        return $productReviews;
    }

    /**
     * @param array $searchResultData
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function mapProductReviewDocument(array $searchResultData)
    {
        $productReviewTransfer = new ProductReviewTransfer();
        $productReviewTransfer->fromArray($searchResultData, true);

        return $productReviewTransfer;
    }
}
