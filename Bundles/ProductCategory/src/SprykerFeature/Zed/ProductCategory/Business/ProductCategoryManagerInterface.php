<?php

namespace SprykerFeature\Zed\ProductCategory\Business;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;

interface ProductCategoryManagerInterface
{
    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleDto $locale);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return int
     * @throws ProductCategoryMappingExistsException
     * @throws MissingProductException
     * @throws MissingCategoryNodeException
     * @throws PropelException
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleDto $locale);
}
