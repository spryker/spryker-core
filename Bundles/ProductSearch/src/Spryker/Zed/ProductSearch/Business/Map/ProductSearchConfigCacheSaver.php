<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface;
use Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToSearchInterface;
use Spryker\Zed\ProductSearch\ProductSearchConfig;

class ProductSearchConfigCacheSaver implements ProductSearchConfigCacheSaverInterface
{

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface
     */
    protected $attributeReader;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToSearchInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\ProductSearchConfig
     */
    protected $productSearchConfig;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToSearchInterface $searchFacade
     * @param \Spryker\Zed\ProductSearch\ProductSearchConfig $productSearchConfig
     */
    public function __construct(AttributeReaderInterface $attributeReader, ProductSearchToSearchInterface $searchFacade, ProductSearchConfig $productSearchConfig)
    {
        $this->attributeReader = $attributeReader;
        $this->searchFacade = $searchFacade;
        $this->productSearchConfig = $productSearchConfig;
    }

    /**
     * @throws \Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException
     *
     * @return void
     */
    public function saveProductSearchConfigCache()
    {
        $searchConfigCacheTransfer = new SearchConfigCacheTransfer();

        $filterTypeConfigs = $this->productSearchConfig->getFilterTypeConfigs();

        $productSearchAttributeTransfers = $this->attributeReader->getAttributeList();
        foreach ($productSearchAttributeTransfers as $productSearchAttributeTransfer) {
            if (!isset($filterTypeConfigs[$productSearchAttributeTransfer->getFilterType()])) {
                throw new InvalidFilterTypeException(sprintf(
                    'Invalid filter type "%s"! Available options are [%s].',
                    $productSearchAttributeTransfer->getFilterType(),
                    implode(', ', array_keys($filterTypeConfigs))
                ));
            }

            $facetConfigTransfer = clone $filterTypeConfigs[$productSearchAttributeTransfer->getFilterType()];

            $facetConfigTransfer
                ->setName($productSearchAttributeTransfer->getKey())
                ->setParameterName($productSearchAttributeTransfer->getKey());

            $searchConfigCacheTransfer->addFacetConfig($facetConfigTransfer);
        }

        $this->searchFacade->saveSearchConfigCache($searchConfigCacheTransfer);
    }

}
