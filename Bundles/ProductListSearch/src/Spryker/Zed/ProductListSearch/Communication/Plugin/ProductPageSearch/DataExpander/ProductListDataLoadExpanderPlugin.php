<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch\DataExpander;

use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 */
class ProductListDataLoadExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        $blacklistIds = $this->getListIds($productData, SpyProductListTableMap::COL_TYPE_BLACKLIST);
        $whitelistIds = $this->getListIds($productData, SpyProductListTableMap::COL_TYPE_WHITELIST);

        $productAbstractPageSearchTransfer->setProductListMap(null);

        if (count($blacklistIds) || count($whitelistIds)) {
            $this->expandProductPageDataWithProductLists($productAbstractPageSearchTransfer, $blacklistIds, $whitelistIds);
        }
    }

    /**
     * @param array $productData
     * @param string $type
     *
     * @return array
     */
    protected function getListIds(array $productData, string $type): array
    {
        return $this->getProductPayload($productData)->getProductLists()[$type] ?? [];
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayload(array $productData): ProductPayloadTransfer
    {
        return $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param int[] $blacklistIds
     * @param int[] $whitelistIds
     *
     * @return void
     */
    protected function expandProductPageDataWithProductLists(
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $blacklistIds,
        array $whitelistIds
    ): void {
        $productAbstractPageSearchTransfer->setProductListMap(
            (new ProductListMapTransfer())
                ->setBlacklists($blacklistIds)
                ->setWhitelists($whitelistIds)
        );
    }
}
