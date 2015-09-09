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
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

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
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param ProductCategoryToProductInterface $productFacade
     * @param ProductCategoryToCategoryInterface $categoryFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToProductInterface $productFacade,
        ProductCategoryToCategoryInterface $categoryFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->productFacade = $productFacade;
        $this->categoryFacade = $categoryFacade;
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
        $idCategoryNode = $this->categoryFacade->getCategoryNodeIdentifier($categoryName, $locale);

        $mappingEntity = $this->locator->productCategory()->entitySpyProductCategory();
        $mappingEntity
            ->setFkAbstractProduct($idAbstractProduct)
            ->setFkCategoryNode($idCategoryNode)
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

}
