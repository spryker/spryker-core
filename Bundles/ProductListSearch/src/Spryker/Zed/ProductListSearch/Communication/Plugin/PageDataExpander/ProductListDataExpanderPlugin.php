<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 */
class ProductListDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    protected const KEY_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

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
        $idProductAbstract = $this->getIdProductAbstract($productData);
        $blacklistIds = $this->getFactory()->getProductListFacade()->getProductAbstractBlacklistIdsIdProductAbstract($idProductAbstract);
        $whitelistIds = $this->getFactory()->getProductListFacade()->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
        $productAbstractPageSearchTransfer->setProductListMap(null);

        if (count($blacklistIds) || count($whitelistIds)) {
            $this->expandProductPageDataWithProductLists($productAbstractPageSearchTransfer, $blacklistIds, $whitelistIds);
        }
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    protected function getIdProductAbstract(array $productData): int
    {
        return $productData[static::KEY_FK_PRODUCT_ABSTRACT];
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
