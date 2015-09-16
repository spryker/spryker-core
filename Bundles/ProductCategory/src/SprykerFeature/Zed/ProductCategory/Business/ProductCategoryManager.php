<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToTouchInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;

class ProductCategoryManager implements ProductCategoryManagerInterface
{

    /**
     * @var ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var ProductCategoryToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductCategoryToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var ProductCategoryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param ProductCategoryToProductInterface $productFacade
     * @param ProductCategoryToCategoryInterface $categoryFacade
     * @param LocatorLocatorInterface $locator
     * @param ProductCategoryToTouchInterface $touchFacade
     */
    public function __construct(
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToProductInterface $productFacade,
        ProductCategoryToCategoryInterface $categoryFacade,
        ProductCategoryToTouchInterface $touchFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->productFacade = $productFacade;
        $this->categoryFacade = $categoryFacade;
        $this->touchFacade = $touchFacade;
        $this->locator = $locator;
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
        $mappingQuery = $this->productCategoryQueryContainer
            ->queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, $locale)
        ;

        return $mappingQuery->count() > 0;
    }

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
        $this->checkMappingDoesNotExist($sku, $categoryName, $locale);

        $idAbstractProduct = $this->productFacade->getAbstractProductIdBySku($sku);
        $idCategory = $this->categoryFacade->getCategoryIdentifier($categoryName, $locale);

        $mappingEntity = $this->locator->productCategory()->entitySpyProductCategory();
        $mappingEntity
            ->setFkAbstractProduct($idAbstractProduct)
            ->setFkCategory($idCategory)    
        ;

        $mappingEntity->save();

        return $mappingEntity->getPrimaryKey();
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws ProductCategoryMappingExistsException
     */
    protected function checkMappingDoesNotExist($sku, $categoryName, LocaleTransfer $locale)
    {
        if ($this->hasProductCategoryMapping($sku, $categoryName, $locale)) {
            throw new ProductCategoryMappingExistsException(
                sprintf(
                    'Tried to create a product category mapping that already exists: Product: %s, Category: %s, Locale: %s',
                    $sku,
                    $categoryName,
                    $locale->getLocaleName()
                )
            );
        }
    }

    /**
     * @param int $idCategory
     *
     * @param LocaleTransfer $locale
     * @return array
     */
    public function getProductsByCategory($idCategory, LocaleTransfer $locale)
    {
        return $this->productCategoryQueryContainer
            ->queryProductsByCategoryId($idCategory, $locale)
            ->orderByFkAbstractProduct()
            ->find()
        ;
    }

    /**
     * @param SpyAbstractProduct $abstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function getCategoriesByAbstractProduct(SpyAbstractProduct $abstractProduct)
    {
        return $this->productCategoryQueryContainer
            ->queryLocalizedProductCategoryMappingByProduct($abstractProduct)
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
        return $this->productCategoryQueryContainer
            ->queryProductCategoryMappingByIds($idCategory, $idAbstractProduct)
            ;
    }

    /**
     * @param int $idCategory
     * @param array $product_ids_to_assign
     *
     * @throws PropelException
     */
    public function createProductCategoryMappings($idCategory, array $product_ids_to_assign)
    {
        foreach ($product_ids_to_assign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOneOrCreate();

            if ($mapping) {
                $mapping->setFkCategory($idCategory);
                $mapping->setFkAbstractProduct($idProduct);
                $mapping->save();

                $this->touchAbstractProductActive($idProduct);
            }
        }
    }

    /**
     * @param int $idCategory
     * @param array $product_ids_to_deassign
     */
    public function removeProductCategoryMappings($idCategory, array $product_ids_to_deassign)
    {
        foreach ($product_ids_to_deassign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if ($mapping) {
                $mapping->delete();

                //yes, Active is correct, it should update touch items, not mark them to delete
                //it's just a change to the mappings and not an actual abstract product
                $this->touchAbstractProductActive($idProduct);
            }
        }
    }

    /**
     * @param int $idCategory
     * @param array $product_order_list
     * @throws PropelException
     */
    public function updateProductMappingsOrder($idCategory, array $product_order_list)
    {
        foreach ($product_order_list as $idProduct => $order) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if ($mapping) {
                $mapping->setFkCategory($idCategory);
                $mapping->setFkAbstractProduct($idProduct);
                $mapping->setProductOrder($order);
                $mapping->save();

                $this->touchAbstractProductActive($idProduct);
            }
        }
    }

    /**
     * @param int $idCategory
     * @param array $product_preconfig_list
     * @throws PropelException
     */
    public function updateProductMappingsPreconfig($idCategory, array $product_preconfig_list)
    {
        foreach ($product_preconfig_list as $idProduct => $idPreconfigProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if ($mapping) {
                $mapping->setFkCategory($idCategory);
                $mapping->setFkAbstractProduct($idProduct);
                $mapping->setFkPreconfigProduct($idPreconfigProduct);
                $mapping->save();

                $this->touchAbstractProductActive($idProduct);
            }
        }
    }

    /**
     * @param int $idAbstractProduct
     */
    protected function touchAbstractProductActive($idAbstractProduct)
    {
        $this->touchFacade->touchActive(ProductCategoryConfig::RESOURCE_TYPE_ABSTRACT_PRODUCT, $idAbstractProduct);
    }

    /**
     * @param int $idAbstractProduct
     */
    protected function touchAbstractProductDeleted($idAbstractProduct)
    {
        $this->touchFacade->touchDeleted(ProductCategoryConfig::RESOURCE_TYPE_ABSTRACT_PRODUCT, $idAbstractProduct);
    }

}
