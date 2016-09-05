<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map;

use Generated\Shared\Transfer\ProductSearchAttributeMapTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;

class ProductSearchAttributeMapCollector implements ProductSearchAttributeMapCollectorInterface
{

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected $searchConfig;

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     */
    public function __construct(SearchConfigInterface $searchConfig)
    {
        $this->searchConfig = $searchConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    public function getProductSearchAttributeMap()
    {
        // TODO: here we ought to collect only dynamic facet filters, isn't getAll() too much?
        $result = [];
        foreach ($this->searchConfig->getFacetConfigBuilder()->getAll() as $facetConfigTransfer) {
            $result[] = (new ProductSearchAttributeMapTransfer())
                ->setAttributeName($facetConfigTransfer->getName())
                ->setTargetFields([$facetConfigTransfer->getFieldName()]);
        }

        return $result;
    }

}
