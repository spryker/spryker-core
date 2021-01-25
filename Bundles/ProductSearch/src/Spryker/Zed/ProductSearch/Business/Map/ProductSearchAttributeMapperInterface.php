<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map;

use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface as ProductSearchExtensionPageMapBuilderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

interface ProductSearchAttributeMapperInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapperInterface::mapDynamicProductAttributesToSearchData()} instead.
     *
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes);

    /**
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributesToSearchData(
        ProductSearchExtensionPageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        array $attributes
    ): PageMapTransfer;
}
