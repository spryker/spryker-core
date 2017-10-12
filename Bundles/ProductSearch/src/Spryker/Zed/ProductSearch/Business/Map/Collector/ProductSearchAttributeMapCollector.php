<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map\Collector;

use Generated\Shared\Transfer\ProductSearchAttributeMapTransfer;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class ProductSearchAttributeMapCollector implements ProductSearchAttributeMapCollectorInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @var \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMap[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected static $rawAttributeMap;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
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
            $attributeName = $attributeMap->getSpyProductAttributeKey()->getKey();

            if (!isset($rawMap[$attributeName])) {
                $rawMap[$attributeName] = [];
            }

            $rawMap[$attributeName][] = $attributeMap->getTargetField();
        }

        return $this->processRawMap($rawMap);
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMap[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRawAttributeMap()
    {
        if (static::$rawAttributeMap === null) {
            static::$rawAttributeMap = $this
                ->productSearchQueryContainer
                ->queryProductSearchAttributeMap()
                ->find();
        }

        return static::$rawAttributeMap;
    }

    /**
     * @param array $attributeMaps
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    protected function processRawMap(array $attributeMaps)
    {
        $result = [];

        foreach ($attributeMaps as $attributeName => $targetFields) {
            $result[] = (new ProductSearchAttributeMapTransfer())
                ->setAttributeName($attributeName)
                ->setTargetFields($targetFields);
        }

        return $result;
    }
}
