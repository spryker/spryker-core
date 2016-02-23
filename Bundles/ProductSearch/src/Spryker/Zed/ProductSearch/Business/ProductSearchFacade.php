<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
{

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     *
     * @return array
     */
    public function enrichProductsWithSearchAttributes(array $productsRaw, array $processedProducts)
    {
        return $this->getFactory()
            ->createProductAttributesTransformer()
            ->buildProductAttributes($productsRaw, $processedProducts);
    }

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function createSearchProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createProductSearchProcessor()
            ->buildProducts($productsRaw, $processedProducts, $locale);
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

}
