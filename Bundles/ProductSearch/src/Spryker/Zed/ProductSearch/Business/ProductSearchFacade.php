<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
{

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function activateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->activateProductSearch($idProduct, $localeCollection);
    }

    /**
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function deactivateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->deactivateProductSearch($idProduct, $localeCollection);
    }

}
