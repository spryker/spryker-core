<?php

namespace SprykerFeature\Zed\ProductSearch\Business\Processor;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface ProductSearchProcessorInterface
{
    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, LocaleDto $locale);
}
