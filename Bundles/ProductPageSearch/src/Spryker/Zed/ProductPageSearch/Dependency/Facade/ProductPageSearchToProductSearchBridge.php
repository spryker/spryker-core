<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

class ProductPageSearchToProductSearchBridge implements ProductPageSearchToProductSearchInterface
{

    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface
     */
    protected $productSearchFacade;

    /**
     * ProductPageSearchToProductSearchBridge constructor.
     *
     * @param \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface $productSearchFacade
     */
    public function __construct($productSearchFacade)
    {
        $this->productSearchFacade = $productSearchFacade;
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
        return $this->productSearchFacade->mapDynamicProductAttributes($pageMapBuilder, $pageMapTransfer, $attributes);
    }

}
