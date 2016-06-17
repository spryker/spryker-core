<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map;

use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

class SearchProductAttributeMapper implements SearchProductAttributeMapperInterface
{

    /**
     * @var \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    protected $attributeMapCollector;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollectorInterface $attributeMapCollector
     */
    public function __construct(SearchProductAttributeMapCollectorInterface $attributeMapCollector)
    {
        $this->attributeMapCollector = $attributeMapCollector;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes)
    {
        $attributeMap = $this->attributeMapCollector->getProductSearchAttributeMap();

        foreach ($attributeMap as $attributeMapTransfer) {
            $attributeName = $attributeMapTransfer->getAttributeName();

            if (!isset($attributes[$attributeName])) {
                continue;
            }

            foreach ($attributeMapTransfer->getTargetFields() as $fieldName) {
                $pageMapBuilder->add($pageMapTransfer, $fieldName, $attributeName, $attributes[$attributeName]);
            }
        }

        return $pageMapTransfer;
    }

}
