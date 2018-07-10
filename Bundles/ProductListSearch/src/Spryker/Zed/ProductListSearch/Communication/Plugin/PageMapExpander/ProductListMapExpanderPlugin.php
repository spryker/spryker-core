<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\PageMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 */
class ProductListMapExpanderPlugin extends AbstractPlugin implements ProductPageMapExpanderInterface
{
    protected const KEY_PRODUCT_LIST_MAP = 'product_list_map';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductPageMap(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $productData, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        if (!isset($productData[static::KEY_PRODUCT_LIST_MAP])) {
            return $pageMapTransfer;
        }
        $productListMap = $this->getProductListSearchData($productData);
        $pageMapTransfer->setProductLists($productListMap);

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductListMapTransfer
     */
    protected function getProductListSearchData(array $productData): ProductListMapTransfer
    {
        $productListMapTransfer = new ProductListMapTransfer();
        $productListMapTransfer->fromArray($productData[static::KEY_PRODUCT_LIST_MAP]);

        return $productListMapTransfer;
    }
}
