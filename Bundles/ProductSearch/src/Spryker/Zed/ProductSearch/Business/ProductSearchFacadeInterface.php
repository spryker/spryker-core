<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductSearchFacadeInterface
{

    /**
     * @api
     *
     * @param array $productsRaw
     * @param array $processedProducts
     *
     * @return array
     */
    public function enrichProductsWithSearchAttributes(array $productsRaw, array $processedProducts);

    /**
     * @api
     *
     * @param array $productsRaw
     * @param array $processedProducts
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function createSearchProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function activateProductSearch($idProduct, array $localeCollection);

    /**
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function deactivateProductSearch($idProduct, array $localeCollection);

}
