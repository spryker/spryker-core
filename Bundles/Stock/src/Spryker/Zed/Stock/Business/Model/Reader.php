<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use InvalidArgumentException;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException;
use Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;
use Spryker\Zed\Stock\StockConfig;

class Reader implements ReaderInterface
{
    const MESSAGE_NO_RESULT = 'no stock set for this sku';
    const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @var \Spryker\Zed\Stock\StockConfig
     */
    protected $stockConfig;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface $productFacade
     * @param \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface $transferMapper
     * @param \Spryker\Zed\Stock\StockConfig $stockConfig
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        StockQueryContainerInterface $queryContainer,
        StockToProductInterface $productFacade,
        StockProductTransferMapperInterface $transferMapper,
        StockConfig $stockConfig,
        StockToStoreFacadeInterface $storeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->transferMapper = $transferMapper;
        $this->stockConfig = $stockConfig;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return array
     */
    public function getStockTypes()
    {
        $stockTypes = $this->queryContainer
            ->queryAllStockTypes()
            ->find();

        return $this->mapStockTypes($stockTypes);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer)
    {
        $storeTransfer->requireName();

        $warehouses = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $stockTypes = $this->queryContainer
            ->queryStockByNames($warehouses)
            ->find();

        return $this->mapStockTypes($stockTypes);
    }

    /**
     * @return array
     */
    public function getWarehouseToStoreMapping()
    {
        $mapping = [];
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $sharedPersistenceWithStores = $currentStoreTransfer->getSharedPersistenceWithStores();
        $sharedPersistenceWithStores[] = $currentStoreTransfer->getName();
        foreach ($this->stockConfig->getStoreToWarehouseMapping() as $storeName => $warehouses) {
            if (!in_array($storeName, $sharedPersistenceWithStores)) {
                continue;
            }
            foreach ($warehouses as $warehouse) {
                $mapping[$warehouse][$storeName] = $storeName;
            }
        }

        return $mapping;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku, StoreTransfer $storeTransfer = null)
    {
        if (!$storeTransfer) {
            $storeTransfer = $this->storeFacade->getCurrentStore();
        }

        $idProduct = $this->productFacade->findProductConcreteIdBySku($sku);
        $warehouses = $this->getStoreWarehouses($storeTransfer->getName());

        $stock = $this->queryContainer
            ->queryStockByNeverOutOfStockAllTypes($idProduct)
            ->useStockQuery()
                 ->filterByName($warehouses, Criteria::IN)
            ->endUse()
            ->findOne();

        return ($stock !== null);
    }

    /**
     * @param string $sku
     *
     * @throws \InvalidArgumentException
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct[]
     */
    public function getStocksProduct($sku)
    {
        $productId = $this->productFacade->findProductConcreteIdBySku($sku);
        $stockEntities = $this->queryContainer
            ->queryStockByProducts($productId)
            ->find();

        if (count($stockEntities) < 1) {
            throw new InvalidArgumentException(self::MESSAGE_NO_RESULT);
        }

        return $stockEntities;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct[]
     */
    public function findProductStocksForStore($sku, StoreTransfer $storeTransfer)
    {
        $productId = $this->productFacade->findProductConcreteIdBySku($sku);
        $warehouses = $this->getStoreWarehouses($storeTransfer->getName());

        return $this->queryContainer
            ->queryStockByProducts($productId)
            ->useStockQuery()
                 ->filterByName($warehouses, Criteria::IN)
            ->endUse()
            ->find();
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    protected function getStoreWarehouses($storeName)
    {
        return $this->stockConfig->getStoreToWarehouseMapping()[$storeName];
    }

    /**
     * @param string $stockType
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getStockTypeIdByName($stockType)
    {
        $stockTypes = $this->queryContainer->queryStockByName($stockType)->findOne();
        if (!$stockTypes) {
            throw new InvalidArgumentException(self::ERROR_STOCK_TYPE_UNKNOWN);
        }

        return $stockTypes->getIdStock();
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->queryContainer
                ->queryStockProductBySkuAndType($sku, $stockType)
                ->count() > 0;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function hastStockProductInStore($sku, StoreTransfer $storeTransfer)
    {
        if (!isset($this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()])) {
            return false;
        }

        $storeWarehouseMapping = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];
        $idProduct = $this->queryContainer
            ->queryStockProductBySkuAndTypes($sku, $storeWarehouseMapping)
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->findOne();

        return $idProduct !== null;
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        $idStockType = $this->getStockTypeIdByName($stockType);
        $idProduct = $this->getProductConcreteIdBySku($sku);

        $stockProductEntity = $this->queryContainer
            ->queryStockProductByStockAndProduct($idStockType, $idProduct)
            ->findOne();

        if ($stockProductEntity === null) {
            throw new StockProductNotFoundException(
                sprintf(
                    'There is no Stock %s for a product with sku: %s',
                    $stockType,
                    $sku
                )
            );
        }

        return $stockProductEntity->getIdStockProduct();
    }

    /**
     * @param int $idStockType
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException
     *
     * @return void
     */
    public function checkStockDoesNotExist($idStockType, $idProduct)
    {
        $stockProductQuery = $this->queryContainer
            ->queryStockProductByStockAndProduct($idStockType, $idProduct);

        if ($stockProductQuery->count() > 0) {
            throw new StockProductAlreadyExistsException(
                'Cannot duplicate entry: this stock type is already set for this product'
            );
        }
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku)
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku)
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }

    /**
     * @param int $idStockProduct
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    public function getStockProductById($idStockProduct)
    {
        $stockProductEntity = $this->queryContainer
            ->queryStockProductByIdStockProduct($idStockProduct)
            ->innerJoinStock()
            ->findOne();

        if ($stockProductEntity === null) {
            throw new StockProductNotFoundException();
        }

        return $stockProductEntity;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]|null
     */
    public function findStockProductsByIdProduct($idProductConcrete, StoreTransfer $storeTransfer)
    {
        $types = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $stockProducts = $this->queryContainer
            ->queryStockByIdProduct($idProductConcrete, $types)
            ->find();

        if (!$stockProducts) {
            return null;
        }

        $products = [];
        foreach ($stockProducts as $stockProductEntity) {
            $stockProductTransfer = (new StockProductTransfer())
                ->fromArray($stockProductEntity->toArray(), true);

            $products[] = $stockProductTransfer;
        }

        return $products;
    }

    /**
     * @param string $stockType
     *
     * @return bool
     */
    protected function hasStockType($stockType)
    {
        return $this->queryContainer
            ->queryStockByName($stockType)
            ->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithStocks(ProductConcreteTransfer $productConcreteTransfer)
    {
        $stockProductCollection = $this->queryContainer
            ->queryStockByProducts($productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete())
            ->innerJoinStock()
            ->find();

        if ($stockProductCollection === null) {
            return $productConcreteTransfer;
        }

        foreach ($stockProductCollection as $stockProductEntity) {
            $stockProductTransfer = $this->transferMapper->convertStockProduct($stockProductEntity);
            $stockProductTransfer->setSku($productConcreteTransfer->getSku());

            $productConcreteTransfer->addStock($stockProductTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $stockTypes
     *
     * @return array
     */
    protected function mapStockTypes(ObjectCollection $stockTypes)
    {
        $types = [];
        foreach ($stockTypes as $stockType) {
            $types[$stockType->getName()] = $stockType->getName();
        }
        return $types;
    }
}
