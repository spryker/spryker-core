<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
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

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     * 
     * @return SpyProductCategoryQuery[]
     */
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

    /**
     * @param int $idCategory
     * @param int $idAbstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function getProductCategoryMappingById($idCategory, $idAbstractProduct)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->getProductCategoryMappingById($idCategory, $idAbstractProduct)
        ;
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     * 
     * @throws PropelException
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->createProductCategoryMappings($idCategory, $productIdsToAssign)
        ;
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToDeassign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToDeassign)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->removeProductCategoryMappings($idCategory, $productIdsToDeassign)
        ;
    }

    /**
     * @param int $idCategory
     * @param array $productOrderList
     * @throws PropelException
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->updateProductMappingsOrder($idCategory, $productOrderList)
        ;
    }

    /**
     * @param int $idCategory
     * @param $productPreconfig
     *
     * @return void
     */
    public function updateProductCategoryPreconfig($idCategory, array $productPreconfig)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->updateProductMappingsPreconfig($idCategory, $productPreconfig)
        ;
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $locale)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->deleteCategoryRecursive($idCategory, $locale)
        ;
    }

    /**
     * @param NodeTransfer $sourceNode
     * @param NodeTransfer $destinationNode
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNode, NodeTransfer $destinationNode, LocaleTransfer $locale)
    {
        $this->getDependencyContainer()
            ->createProductCategoryManager()
            ->moveCategoryChildrenAndDeleteNode($sourceNode, $destinationNode, $locale)
        ;
    }
}
