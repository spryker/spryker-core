<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\ProductPageSearch\Elasticsearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 */
class ProductReviewMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * {@inehritDoc}
     * - Adds product review related data to product abstract search data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ) {
        $rating = $this->getRating($productData);

        if (!$rating) {
            return $pageMapTransfer;
        }

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, 'rating', $rating)
            ->addSearchResultData($pageMapTransfer, 'review_count', $productData['review_count'])
            ->addIntegerFacet($pageMapTransfer, 'rating', (int)($rating * 100))
            ->addIntegerSort($pageMapTransfer, 'rating', (int)($rating * 100));

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return float
     */
    protected function getRating(array $productData)
    {
        return round($productData['average_rating'], 2);
    }
}
