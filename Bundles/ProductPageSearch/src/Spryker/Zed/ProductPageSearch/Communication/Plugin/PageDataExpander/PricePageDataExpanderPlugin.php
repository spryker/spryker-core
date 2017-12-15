<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 */
class PricePageDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{

    /**
     * @param array $productData
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $price = $this->getFactory()->getPriceFacade()->getPriceBySku($productData['SpyProductAbstract']['sku']);
        $priceProducts = $this->getFactory()->getPriceFacade()->findPricesBySku($productData['SpyProductAbstract']['sku']);

        $productAbstractPageSearchTransfer->setPrice($price);

        foreach ($priceProducts as $priceProduct) {
            $productAbstractPageSearchTransfer->addPriceProducts($priceProduct);
        }
    }

}
