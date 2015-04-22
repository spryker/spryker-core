<?php

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Propel\Runtime\Exception\PropelException;

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
     * @param string $localeId
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, $localeId)
    {
        if (!$this->productFacade->hasConcreteProduct($sku)) {
            return false;
        }

        if (!$this->categoryFacade->hasCategoryNode($categoryName, $localeId)) {
            return false;
        }

        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);
        $idCategoryNode = $this->categoryFacade->getCategoryNodeIdentifier($categoryName, $localeId);

        $mappingQuery = $this->productCategoryQueryContainer
            ->queryProductCategoryMappingByIds($idProduct, $idCategoryNode)
        ;

        return $mappingQuery->count() > 0;
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param string $localeId
     * @return int
     *
     * @throws ProductCategoryMappingExistsException
     * @throws MissingProductException
     * @throws MissingCategoryNodeException
     * @throws PropelException
     */
    public function createProductCategoryMapping($sku, $categoryName, $localeId)
    {
        $this->checkMappingDoesNotExist($sku, $categoryName, $localeId);

        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);
        $idCategoryNode = $this->categoryFacade->getCategoryNodeIdentifier($categoryName, $localeId);

        $mappingEntity = $this->locator->productCategory()->entitySpyProductCategory();
        $mappingEntity
            ->setFkProduct($idProduct)
            ->setFkCategoryNode($idCategoryNode)
        ;

        $mappingEntity->save();

        return $mappingEntity->getPrimaryKey();
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param int $localeId
     *
     * @throws ProductCategoryMappingExistsException
     */
    protected function checkMappingDoesNotExist($sku, $categoryName, $localeId)
    {
        if ($this->hasProductCategoryMapping($sku, $categoryName, $localeId)) {
            throw new ProductCategoryMappingExistsException(
                sprintf(
                    'Tried to create a product category mapping that already exists: Product: %s, Category: %s, Locale: %s',
                    $sku,
                    $categoryName,
                    $localeId
                )
            );
        }
    }
}
