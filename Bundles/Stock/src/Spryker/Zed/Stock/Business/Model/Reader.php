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
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Spryker\Zed\Stock\Business\Exception\MissingProductException;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException;
use Spryker\Zed\Stock\Business\Exception\StockWarehouseMappingException;
use Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;
use Spryker\Zed\Stock\StockConfig;

class Reader implements ReaderInterface
{
    public const MESSAGE_NO_RESULT = 'no stock set for this sku';
    public const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

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
     * @return string[]
     */
    public function getStockTypes(): array
    {
        $stockTypes = $this->queryContainer
            ->queryAllStockTypes()
            ->find()
            ->getData();

        return $this->mapStockNames($stockTypes);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();

        $warehouses = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $stockTypes = $this->queryContainer
            ->queryStockByNames($warehouses)
            ->find()
            ->getData();

        return $this->mapStockNames($stockTypes);
    }

    /**
     * @return array
     */
    public function getWarehouseToStoreMapping(): array
    {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $storesWithSharedPersistence = $currentStoreTransfer->getStoresWithSharedPersistence();
        $storesWithSharedPersistence[] = $currentStoreTransfer->getName();

        $mapping = [];
        foreach ($this->stockConfig->getStoreToWarehouseMapping() as $storeName => $warehouses) {
            if (!in_array($storeName, $storesWithSharedPersistence)) {
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
     *
     * @return bool
     */
    public function isNeverOutOfStock(string $sku): bool
    {
        $idProduct = $this->productFacade->findProductConcreteIdBySku($sku);

        return $this->queryContainer
            ->queryStockByNeverOutOfStockAllTypes($idProduct)
            ->select(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT)
            ->exists();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isNeverOutOfStockForStore(string $sku, StoreTransfer $storeTransfer): bool
    {
        $idProduct = $this->productFacade->findProductConcreteIdBySku($sku);
        $stockNames = $this->getStoreWarehouses($storeTransfer->getName());

        return $this->queryContainer
            ->queryStockByNeverOutOfStockAllTypesForStockNames($idProduct, $stockNames)
            ->select(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT)
            ->exists();
    }

    /**
     * @param string $sku
     *
     * @throws \InvalidArgumentException
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct[]
     */
    public function getStocksProduct(string $sku): array
    {
        $productId = $this->productFacade->findProductConcreteIdBySku($sku);
        $stockEntities = $this->queryContainer
            ->queryStockByProducts($productId)
            ->find()
            ->getData();

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
    public function findProductStocksForStore(string $sku, StoreTransfer $storeTransfer): array
    {
        $productId = $this->productFacade->findProductConcreteIdBySku($sku);
        $storeNames = $this->getStoreWarehouses($storeTransfer->getName());

        return $this->queryContainer
            ->queryStockByProductsForStockNames($productId, $storeNames)
            ->find()
            ->getData();
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockWarehouseMappingException
     *
     * @return string[]
     */
    protected function getStoreWarehouses($storeName)
    {
        if (!isset($this->stockConfig->getStoreToWarehouseMapping()[$storeName])) {
            throw new StockWarehouseMappingException(
                sprintf(
                    'Warehouse mapping is not provided for store %s. You can configure it in %s::getStoreToWarehouseMapping',
                    $storeName,
                    StockConfig::class
                )
            );
        }

        return $this->stockConfig->getStoreToWarehouseMapping()[$storeName];
    }

    /**
     * @param string $stockType
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getStockTypeIdByName(string $stockType): int
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
    public function hasStockProduct(string $sku, string $stockType): bool
    {
        return $this->queryContainer
            ->queryStockProductBySkuAndType($sku, $stockType)
            ->select(SpyStockTableMap::COL_ID_STOCK)
            ->exists();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function hastStockProductInStore(string $sku, StoreTransfer $storeTransfer): bool
    {
        if (!isset($this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()])) {
            return false;
        }

        $storeWarehouseMapping = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        return $this->queryContainer
            ->queryStockProductBySkuAndTypes($sku, $storeWarehouseMapping)
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->exists();
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return int
     */
    public function getIdStockProduct(string $sku, string $stockType): int
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
    public function checkStockDoesNotExist($idStockType, $idProduct): void
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
    public function findProductAbstractIdBySku(string $sku): ?int
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku(string $sku): int
    {
        $productConcreteId = $this->productFacade->findProductConcreteIdBySku($sku);

        if ($productConcreteId === null) {
            throw new MissingProductException();
        }

        return $productConcreteId;
    }

    /**
     * @param int $idStockProduct
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    public function getStockProductById($idStockProduct): SpyStockProduct
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
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdProduct($idProductConcrete): array
    {
        $stockProducts = $this->queryContainer
            ->queryStockByIdProduct($idProductConcrete)
            ->find();

        if (count($stockProducts) === 0) {
            throw new StockProductNotFoundException();
        }

        $products = [];
        foreach ($stockProducts as $stockProductEntity) {
            $stockProductTransfer = new StockProductTransfer();
            $stockProductTransfer->fromArray($stockProductEntity->toArray(), true);
            $products[] = $stockProductTransfer;
        }

        return $products;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findStockProductsByIdProductForStore($idProductConcrete, StoreTransfer $storeTransfer): array
    {
        $types = $this->stockConfig->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        /** @var \Orm\Zed\Stock\Persistence\SpyStockProduct[] $stockProducts */
        $stockProducts = $this->queryContainer
            ->queryStockByIdProductAndTypes($idProductConcrete, $types)
            ->find();

        if (!$stockProducts) {
            return [];
        }

        $productTransferCollection = [];
        foreach ($stockProducts as $stockProductEntity) {
            $stockProductTransfer = (new StockProductTransfer())
                ->fromArray($stockProductEntity->toArray(), true);

            $productTransferCollection[] = $stockProductTransfer;
        }

        return $productTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithStocks(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        /** @var \Orm\Zed\Stock\Persistence\SpyStockProduct[] $stockProductCollection */
        $stockProductCollection = $this->queryContainer
            ->queryStockByProducts($productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete())
            ->innerJoinStock()
            ->find();

        if (!$stockProductCollection) {
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
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockCollection
     *
     * @return string[]
     */
    protected function mapStockNames(array $stockCollection): array
    {
        $types = [];
        foreach ($stockCollection as $stockEntity) {
            $types[$stockEntity->getName()] = $stockEntity->getName();
        }

        return $types;
    }
}
