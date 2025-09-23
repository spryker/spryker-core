<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Dependency\Facade;

class ProductLabelSearchToProductPageSearchAdapter implements ProductLabelSearchToProductPageSearchInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    protected $productPageSearchFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface $productPageSearchFacade
     */
    public function __construct($productPageSearchFacade)
    {
        $this->productPageSearchFacade = $productPageSearchFacade;
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productAbstractIdTimestampMap): void
    {
        if (!method_exists($this->productPageSearchFacade, 'publishWithTimestamp') === false) {
            $this->productPageSearchFacade->refresh(array_keys($productAbstractIdTimestampMap));
        }

        $this->productPageSearchFacade->publishWithTimestamp($productAbstractIdTimestampMap);
    }
}
