<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

/**
 * @property ProductCategoryDependencyContainer $dependencyContainer
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 * @method ProductCategoryManager createProductManager()
 */
class ProductCategoryFacade extends AbstractFacade
{

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws ProductCategoryMappingExistsException
     * @throws MissingProductException
     * @throws MissingCategoryNodeException
     * @throws PropelException
     *
     * @return int
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->createProductCategoryMapping($sku, $categoryName, $locale)
        ;
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->hasProductCategoryMapping($sku, $categoryName, $locale)
        ;
    }
    
    public function getProductsByCategory($idCategory, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->getProductsByCategory($idCategory, $locale)
        ;
    }

    /**
     * @param SpyAbstractProduct $abstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function getCategoriesByAbstractProduct(SpyAbstractProduct $abstractProduct)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->getCategoriesByAbstractProduct($abstractProduct)
        ;
    }

}
