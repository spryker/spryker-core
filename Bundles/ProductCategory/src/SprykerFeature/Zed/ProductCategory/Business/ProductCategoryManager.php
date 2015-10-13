<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainerInterface;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\CmsToCategoryInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToTouchInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;

class ProductCategoryManager implements ProductCategoryManagerInterface
{

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

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
     * @var CmsToCategoryInterface
     */
    protected $cmsFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param ProductCategoryToProductInterface $productFacade
     * @param ProductCategoryToCategoryInterface $categoryFacade
     * @param ProductCategoryToTouchInterface $touchFacade
     * @param CmsToCategoryInterface $cmsFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToProductInterface $productFacade,
        ProductCategoryToCategoryInterface $categoryFacade,
        ProductCategoryToTouchInterface $touchFacade,
        CmsToCategoryInterface $cmsFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->productFacade = $productFacade;
        $this->categoryFacade = $categoryFacade;
        $this->touchFacade = $touchFacade;
        $this->cmsFacade = $cmsFacade;
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
     * @param LocaleTransfer $locale
     *
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
     * @param array $productIdsToAssign
     *
     * @throws PropelException
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        foreach ($productIdsToAssign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOneOrCreate();

            if (null === $mapping) {
                continue;
            }

            $mapping->setFkCategory($idCategory);
            $mapping->setFkAbstractProduct($idProduct);
            $mapping->save();

            $this->touchAbstractProductActive($idProduct);
        }
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToDeassign
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToDeassign)
    {
        foreach ($productIdsToDeassign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if (null === $mapping) {
                continue;
            }

            $mapping->delete();

            //yes, Active is correct, it should update touch items, not mark them to delete
            //it's just a change to the mappings and not an actual abstract product
            $this->touchAbstractProductActive($idProduct);
        }
    }

    /**
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @throws PropelException
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList)
    {
        foreach ($productOrderList as $idProduct => $order) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if (null === $mapping) {
                continue;
            }

            $mapping->setFkCategory($idCategory);
            $mapping->setFkAbstractProduct($idProduct);
            $mapping->setProductOrder($order);
            $mapping->save();

            $this->touchAbstractProductActive($idProduct);
        }
    }

    /**
     * @param int $idCategory
     * @param array $productPreconfigList
     *
     * @throws PropelException
     */
    public function updateProductMappingsPreconfig($idCategory, array $productPreconfigList)
    {
        foreach ($productPreconfigList as $idProduct => $idPreconfigProduct) {
            $idPreconfigProduct = (int) $idPreconfigProduct;
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if (null === $mapping) {
                continue;
            }

            $idPreconfigProduct = $idPreconfigProduct <= 0 ? null : $idPreconfigProduct;
            $mapping->setFkCategory($idCategory);
            $mapping->setFkAbstractProduct($idProduct);
            $mapping->setFkPreconfigProduct($idPreconfigProduct);
            $mapping->save();

            $this->touchAbstractProductActive($idProduct);
        }
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     */
    public function deleteCategoryFull(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        //remove product mappings
        $assignedProducts = $this->productCategoryQueryContainer
            ->queryProductCategoryMappingsByCategoryId($category->getIdCategory())
            ->find()
        ;

        $productsToDeAssign = [];
        foreach ($assignedProducts as $mapping) {
            $productsToDeAssign[] = $mapping->getFkAbstractProduct();
        }

        //product mappings
        $this->removeProductCategoryMappings($category->getIdCategory(), $productsToDeAssign);

        //url/path mappings
        //update touch

        //product mappings
        //update touch

        $categoryNodes = $this->categoryQueryContainer
            ->queryAllNodesByCategoryId($category->getIdCategory())
            ->find()
        ;

        foreach ($categoryNodes as $node) {
            $this->cmsFacade->updateBlocksAssignedToDeletedCategoryNode($node->getIdCategoryNode());
            $this->categoryFacade->deleteNode($node->getIdCategoryNode(), $locale);
        }

        $this->categoryFacade->deleteCategory($category->getIdCategory());


        //remove paths, url, regenrate menu

        $connection->commit();
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
