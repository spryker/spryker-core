<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
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
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategory;
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
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param ProductCategoryToProductInterface $productFacade
     * @param ProductCategoryToCategoryInterface $categoryFacade
     * @param ProductCategoryToTouchInterface $touchFacade
     * @param CmsToCategoryInterface $cmsFacade
     * @param LocatorLocatorInterface $locator
     * @param ConnectionInterface $connection
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToProductInterface $productFacade,
        ProductCategoryToCategoryInterface $categoryFacade,
        ProductCategoryToTouchInterface $touchFacade,
        CmsToCategoryInterface $cmsFacade,
        LocatorLocatorInterface $locator,
        ConnectionInterface $connection
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->productFacade = $productFacade;
        $this->categoryFacade = $categoryFacade;
        $this->touchFacade = $touchFacade;
        $this->cmsFacade = $cmsFacade;
        $this->locator = $locator;
        $this->connection = $connection;
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
     *
     * @return void
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
     * @return SpyProductCategory[]
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
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign)
    {
        foreach ($productIdsToUnAssign as $idProduct) {
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
     * @param array $productIdsToAssign
     *
     * @throws PropelException
     *
     * @return void
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
     * @param array $productOrderList
     *
     * @throws PropelException
     *
     * @return void
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
     * @param array $productPreConfigList
     *
     * @throws PropelException
     *
     * @return void
     */
    public function updateProductMappingsPreConfig($idCategory, array $productPreConfigList)
    {
        foreach ($productPreConfigList as $idProduct => $idPreconfigProduct) {
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
     * @param NodeTransfer $sourceNodeTransfer
     * @param NodeTransfer $destinationNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNodeTransfer, NodeTransfer $destinationNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $this->connection->beginTransaction();

        $children = $this->categoryQueryContainer
            ->queryFirstLevelChildren($sourceNodeTransfer->getIdCategoryNode())
            ->find()
        ;

        foreach ($children as $child) {
            $childTransfer = (new NodeTransfer())->fromArray($child->toArray());
            $childTransfer->setFkParentCategoryNode($destinationNodeTransfer->getIdCategoryNode());
            $this->categoryFacade->updateCategoryNode($childTransfer, $localeTransfer);
        }

        $this->removeExtraParents($sourceNodeTransfer->getFkCategory(), $localeTransfer);

        $this->categoryFacade->deleteNode($sourceNodeTransfer->getIdCategoryNode(), $localeTransfer, false);

        $this->connection->commit();
    }

    /**
     * @param $idCategory
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function removeExtraParents($idCategory, LocaleTransfer $localeTransfer)
    {
        $extraParents = $this->categoryQueryContainer
            ->queryNotMainNodesByCategoryId($idCategory)
            ->find()
        ;

        foreach ($extraParents as $parent) {
            $this->categoryFacade->deleteNode($parent->getIdCategoryNode(), $localeTransfer);
        }
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function addCategory(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $this->connection->beginTransaction();

        $categoryTransfer->setIsActive(true);
        $categoryTransfer->setIsInMenu(true);
        $categoryTransfer->setIsClickable(true);

        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $localeTransfer);

        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsMain(true);

        $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $localeTransfer);

        $this->connection->commit();

        return $idCategory;
    }

    /**
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer)
    {
        $this->connection->beginTransaction();

        if ($deleteChildren) {
            $this->deleteCategoryRecursive($idCategoryNode, $localeTransfer);
        } else {
            $sourceTransfer = $this->categoryFacade->getNodeById($idCategoryNode);

            $destinationEntity = $this->categoryFacade->getNodeById($fkParentCategoryNode);

            $sourceNodeTransfer = (new NodeTransfer())
                ->fromArray($sourceTransfer->toArray());

            $destinationNodeTransfer = (new NodeTransfer())
                ->fromArray($destinationEntity->toArray());

            $this->moveCategoryChildrenAndDeleteNode($sourceNodeTransfer, $destinationNodeTransfer, $localeTransfer);
            $this->deleteCategoryRecursive($idCategoryNode, $localeTransfer);
        }

        $this->connection->commit();
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $localeTransfer)
    {
        $this->connection->beginTransaction();

        $this->removeMappings($idCategory);

        $categoryNodes = $this->categoryQueryContainer
            ->queryAllNodesByCategoryId($idCategory)
            ->find()
        ;

        foreach ($categoryNodes as $node) {
            $this->cmsFacade->updateBlocksAssignedToDeletedCategoryNode($node->getIdCategoryNode()); //TODO: https://spryker.atlassian.net/browse/CD-540

            $children = $this->categoryQueryContainer
                ->queryFirstLevelChildren($node->getIdCategoryNode())
                ->find()
            ;

            foreach ($children as $child) {
                $this->deleteCategoryRecursive($child->getFkCategory(), $localeTransfer);
            }

            $nodeExists = $this->categoryQueryContainer
                ->queryNodeById($node->getIdCategoryNode())
                ->count() > 0;

            if ($nodeExists) {
                $this->categoryFacade->deleteNode($node->getIdCategoryNode(), $localeTransfer, true);
            }
        }

        $this->categoryFacade->deleteCategory($idCategory);

        $this->connection->commit();
    }

    /**
     * @param $idCategory
     *
     * @return void
     */
    protected function removeMappings($idCategory)
    {
        $assignedProducts = $this->productCategoryQueryContainer
            ->queryProductCategoryMappingsByCategoryId($idCategory)
            ->find()
        ;

        $productIdsToUnAssign = [];
        foreach ($assignedProducts as $mapping) {
            $productIdsToUnAssign[] = $mapping->getFkAbstractProduct();
        }
        $this->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    protected function touchAbstractProductActive($idAbstractProduct)
    {
        $this->touchFacade->touchActive(ProductCategoryConfig::RESOURCE_TYPE_ABSTRACT_PRODUCT, $idAbstractProduct);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    protected function touchAbstractProductDeleted($idAbstractProduct)
    {
        $this->touchFacade->touchDeleted(ProductCategoryConfig::RESOURCE_TYPE_ABSTRACT_PRODUCT, $idAbstractProduct);
    }

}
