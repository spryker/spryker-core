<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade getFacade()
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 */
class ProductReviewDataLoaderExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productPayloadTransfer = $this->getProductPayloadTransfer($productData);

        $productAbstractPageSearchTransfer->setReviewCount($productPayloadTransfer->getReviewCount());
        $productAbstractPageSearchTransfer->setAverageRating($productPayloadTransfer->getAverageRating());
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $productData): ProductPayloadTransfer
    {
        return $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA];
    }
}
