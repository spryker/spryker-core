<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductSearchProcessorInterface
{

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale);

}
