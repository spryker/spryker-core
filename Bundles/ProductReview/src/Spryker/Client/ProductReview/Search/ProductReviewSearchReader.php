<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Search;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class ProductReviewSearchReader implements ProductReviewSearchReaderInterface
{
    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected $searchQueryPlugin;

    /**
     * @var \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected $searchResultFormatterPlugins;

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $productReviewsQueryPlugin
     * @param \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface $searchClient
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $searchResultFormatterPlugins
     */
    public function __construct(
        QueryInterface $productReviewsQueryPlugin,
        ProductReviewToSearchInterface $searchClient,
        array $searchResultFormatterPlugins
    ) {
        $this->searchQueryPlugin = $productReviewsQueryPlugin;
        $this->searchClient = $searchClient;
        $this->searchResultFormatterPlugins = $searchResultFormatterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviews(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer): array
    {
        return $this->searchClient->search(
            $this->searchQueryPlugin,
            $this->searchResultFormatterPlugins,
            $productReviewSearchRequestTransfer->getRequestParams()
        );
    }
}
