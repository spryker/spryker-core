<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;
use Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainer;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacadeInterface getFacade()
 */
class ProductReviewDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
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
        $productReviewArray = $this->getQueryContainer()
            ->queryProductReviewRatingByIdAbstractProduct($productData['fk_product_abstract'])
            ->findOne();

        $productAbstractPageSearchTransfer->setAverageRating($productReviewArray[ProductReviewSearchQueryContainer::FIELD_AVERAGE_RATING]);
        $productAbstractPageSearchTransfer->setReviewCount($productReviewArray[ProductReviewSearchQueryContainer::FIELD_COUNT]);
    }
}
