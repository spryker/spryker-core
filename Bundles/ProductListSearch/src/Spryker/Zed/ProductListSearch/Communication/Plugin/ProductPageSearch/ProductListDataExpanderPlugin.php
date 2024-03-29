<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch\DataExpander\ProductListDataLoadExpanderPlugin} instead.
 *
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        $idProductAbstract = $this->getIdProductAbstract($productData);
        $blacklistIds = $this->getFactory()->getProductListFacade()->getProductBlacklistIdsByIdProductAbstract($idProductAbstract);
        $whitelistIds = $this->getFactory()->getProductListFacade()->getProductWhitelistIdsByIdProductAbstract($idProductAbstract);
        $productAbstractPageSearchTransfer->setProductListMap(null);

        if (count($blacklistIds) || count($whitelistIds)) {
            $this->expandProductPageDataWithProductLists($productAbstractPageSearchTransfer, $blacklistIds, $whitelistIds);
        }
    }

    /**
     * @param array<string, mixed> $productData
     *
     * @return int
     */
    protected function getIdProductAbstract(array $productData): int
    {
        return $productData[static::KEY_FK_PRODUCT_ABSTRACT];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array<int> $blacklistIds
     * @param array<int> $whitelistIds
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
                ->setWhitelists($whitelistIds),
        );
    }
}
