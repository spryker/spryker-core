<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductCategorySearch\Communication\Plugin\ProductPageSearch\ProductCategoryPageDataLoaderPlugin} instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class CategoryPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $productPageLoadTransfer)
    {
        $payloadTransfers = $this->setProductCategories($productPageLoadTransfer->getProductAbstractIds(), $productPageLoadTransfer->getPayloadTransfers());
        $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);

        return $productPageLoadTransfer;
    }

    /**
     * @param array<int> $productAbstractIds
     * @param array<\Generated\Shared\Transfer\ProductPayloadTransfer> $payloadTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPayloadTransfer>
     */
    protected function setProductCategories(array $productAbstractIds, array $payloadTransfers): array
    {
        $query = $this->getQueryContainer()->queryProductCategoriesByProductAbstractIds($productAbstractIds);

        $productCategoryEntities = $query->find();
        $formattedProductCategories = [];
        foreach ($productCategoryEntities as $productCategoryEntity) {
            $formattedProductCategories[$productCategoryEntity->getFkProductAbstract()][] = $productCategoryEntity;
        }

        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($formattedProductCategories[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $categories = $formattedProductCategories[$payloadTransfer->getIdProductAbstract()];
            $payloadTransfer->setCategories($categories);
        }

        return $payloadTransfers;
    }
}
