<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\StockProduct;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Spryker\Zed\Stock\Business\Exception\MissingProductException;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException;
use Spryker\Zed\Stock\Business\Stock\StockReaderInterface;
use Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;

class StockProductReader implements StockProductReaderInterface
{
    public const MESSAGE_NO_RESULT = 'no stock set for this sku';

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Stock\Business\Stock\StockReaderInterface
     */
    protected $stockReader;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface $productFacade
     * @param \Spryker\Zed\Stock\Business\Stock\StockReaderInterface $stockReader
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface $transferMapper
     */
    public function __construct(
        StockToProductInterface $productFacade,
        StockReaderInterface $stockReader,
        StockQueryContainerInterface $queryContainer,
        StockRepositoryInterface $stockRepository,
        StockProductTransferMapperInterface $transferMapper
    ) {
        $this->productFacade = $productFacade;
        $this->stockReader = $stockReader;
        $this->queryContainer = $queryContainer;
        $this->stockRepository = $stockRepository;
        $this->transferMapper = $transferMapper;
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
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductAbstractNeverOutOfStockForStore(string $abstractSku, StoreTransfer $storeTransfer): bool
    {
        $storeTransfer->requireName();

        return $this->stockRepository->isProductAbstractNeverOutOfStockForStore($abstractSku, $storeTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStocksProduct(string $sku): array
    {
        return $this->stockRepository->getStockProductsByProductConcreteSku($sku);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findProductStocksForStore(string $sku, StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();

        return $this->stockRepository->findProductStocksForStore($sku, $storeTransfer);
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductByProductAbstractSkuForStore(string $abstractSku, StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();

        return $this->stockRepository->getStockProductByProductAbstractSkuForStore($abstractSku, $storeTransfer);
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
        $storeWarehouseMapping = $this->getStoreWarehouses($storeTransfer->getName());

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
        $idStockType = $this->stockReader->getStockTypeIdByName($stockType);
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
            ->useStockQuery()
            ->filterByIsActive(true)
            ->endUse()
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
        $storeTransfer->requireName();
        $stockNames = $this->stockRepository->getStockNamesForStore($storeTransfer->getName());

        /** @var \Orm\Zed\Stock\Persistence\SpyStockProduct[] $stockProducts */
        $stockProducts = $this->queryContainer
            ->queryStockByIdProductAndTypes($idProductConcrete, $stockNames)
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
            ->useStockQuery()
            ->filterByIsActive(true)
            ->endUse()
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
     * @param string $storeName
     *
     * @return string[]
     */
    protected function getStoreWarehouses(string $storeName): array
    {
        return $this->stockRepository->getStockNamesForStore($storeName);
    }
}
