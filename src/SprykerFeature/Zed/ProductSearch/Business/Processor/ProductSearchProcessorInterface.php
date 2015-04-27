<?php
namespace SprykerFeature\Zed\ProductSearch\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;

/**
 * Class ProductSearchProcessor
 *
 * @package SprykerFeature\Zed\ProductSearch\Business\Processor
 */
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
