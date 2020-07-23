<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @uses \Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\ProductConcreteCatalogSearchResultFormatterPlugin::NAME
     */
    protected const FORMATTED_RESULT_KEY = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * @var \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface $catalogClient
     */
    public function __construct(ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface $catalogClient)
    {
        $this->catalogClient = $catalogClient;
    }

    /**
     * @param int $idProductList
     * @param int $ipp
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductListId(int $idProductList, int $ipp = 1000): array
    {
        $productConcreteCriteriaFilterTransfer = (new ProductConcreteCriteriaFilterTransfer())
            ->setRequestParams([
                'idProductList' => $idProductList,
                'ipp' => $ipp,
            ]);

        $searchResult = $this->catalogClient->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);
        $productConcretePageSearchTransfers = $searchResult[static::FORMATTED_RESULT_KEY];

        if (!$productConcretePageSearchTransfers) {
            return [];
        }

        $productConcreteIds = [];
        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            /** @var \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer */
            $productConcreteIds[] = $productConcretePageSearchTransfer->getFkProduct();
        }

        return array_filter($productConcreteIds);
    }
}
