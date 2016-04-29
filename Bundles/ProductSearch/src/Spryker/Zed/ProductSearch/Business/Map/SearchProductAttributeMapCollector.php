<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map;

use Generated\Shared\Transfer\ProductSearchAttributeMapTransfer;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchProductAttributeMapCollector implements SearchProductAttributeMapCollectorInterface
{

    /**
     * @var ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    public function getProductSearchAttributeMap()
    {
        $rawMap = [];

        foreach ($this->getRawAttributeMap() as $attributeMap) {
            $attributeName = $attributeMap->getSpyProductAttributesMetadata()->getKey();

            if (!isset($rawMap[$attributeName])) {
                $rawMap[$attributeName] = [];
            }

            $rawMap[$attributeName][] = $attributeMap->getTargetField();
        }

        return $this->processRawMap($rawMap);
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapping[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRawAttributeMap()
    {
        $attributeMapping = $this
            ->productSearchQueryContainer
            ->queryProductSearchAttributeMapping()
            ->find();

        return $attributeMapping;
    }

    /**
     * @param array $attributeMappings
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    protected function processRawMap(array $attributeMappings)
    {
        $result = [];

        foreach ($attributeMappings as $attributeName => $targetFields) {
            $result[] = (new ProductSearchAttributeMapTransfer())
                ->setAttributeName($attributeName)
                ->setTargetFields($targetFields);
        }

        return $result;
    }

}
