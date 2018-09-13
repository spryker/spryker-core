<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductPageSearch\ProductPageSearchFactory getFactory()
 */
class ProductPageSearchClient extends AbstractClient implements ProductPageSearchClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $this->setSearchFields($productConcreteCriteriaFilterTransfer, [PageIndexMap::FULL_TEXT_BOOSTED]);

        return $this->search($productConcreteCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesBySku(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $this->setSearchFields($productConcreteCriteriaFilterTransfer, [PageIndexMap::SUGGESTION_SKU]);

        return $this->search($productConcreteCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     * @param array $searchFields
     *
     * @return void
     */
    protected function setSearchFields(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer, array $searchFields): void
    {
        if ($productConcreteCriteriaFilterTransfer->getFilter() === null) {
            $productConcreteCriteriaFilterTransfer->setFilter(new FilterTransfer());
        }

        $productConcreteCriteriaFilterTransfer->getFilter()->setSearchFields($searchFields);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    protected function search(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $searchQuery = $this->getFactory()->createProductConcretePageSearchQuery($productConcreteCriteriaFilterTransfer);
        $resultFormatters = $this->getFactory()->getProductConcretePageSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters);
    }
}
