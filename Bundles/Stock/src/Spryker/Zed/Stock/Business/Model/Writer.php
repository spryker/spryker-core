<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class Writer implements WriterInterface
{
    const TOUCH_STOCK_TYPE = 'stock-type';
    const TOUCH_STOCK_PRODUCT = 'stock-product';
    const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Stock\Business\Model\Reader
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected $stockUpdateHandlerPlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Stock\Business\Model\ReaderInterface $readerInterface
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface $touchFacade
     * @param \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[] $stockUpdateHandlerPlugins
     */
    public function __construct(
        StockQueryContainerInterface $queryContainer,
        ReaderInterface $readerInterface,
        StockToTouchInterface $touchFacade,
        array $stockUpdateHandlerPlugins
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $readerInterface;
        $this->touchFacade = $touchFacade;
        $this->stockUpdateHandlerPlugins = $stockUpdateHandlerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $stockEntity = new SpyStock();
        $stockEntity
            ->setName($stockTypeTransfer->getName())
            ->save();
        $this->insertActiveTouchRecordStockType($stockEntity);

        $this->queryContainer->getConnection()->commit();

        return $stockEntity->getPrimaryKey();
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $idStockType = $this->reader->getStockTypeIdByName($transferStockProduct->getStockType());
        $idProduct = $this->reader->getProductConcreteIdBySku($transferStockProduct->getSku());
        $this->reader->checkStockDoesNotExist($idStockType, $idProduct);
        $idStockProduct = $this->saveStockProduct($transferStockProduct, $idStockType, $idProduct);
        $this->handleStockUpdatePlugins($transferStockProduct->getSku());

        $this->queryContainer->getConnection()->commit();

        return $idStockProduct;
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $idProduct = $this->reader->getProductConcreteIdBySku($transferStockProduct->getSku());
        $idStock = $this->reader->getStockTypeIdByName($transferStockProduct->getStockType());
        $stockProductEntity = $this->reader->getStockProductById($transferStockProduct->getIdStockProduct());

        $stockProductEntity
            ->setFkStock($idStock)
            ->setFkProduct($idProduct)
            ->setQuantity($transferStockProduct->getQuantity())
            ->setIsNeverOutOfStock($transferStockProduct->getIsNeverOutOfStock())
            ->save();

        $this->insertActiveTouchRecordStockProduct($stockProductEntity);
        $this->handleStockUpdatePlugins($transferStockProduct->getSku());

        $this->queryContainer->getConnection()->commit();

        return $stockProductEntity->getPrimaryKey();
    }

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     *
     * @return void
     */
    public function decrementStock($sku, $stockType, $decrementBy = 1)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $idProduct = $this->reader->getProductConcreteIdBySku($sku);
        $idStock = $this->reader->getStockTypeIdByName($stockType);
        $stockProductEntity = $this->queryContainer
            ->queryStockProductByStockAndProduct($idStock, $idProduct)
            ->findOneOrCreate();

        $stockProductEntity->decrement($decrementBy);
        $this->insertActiveTouchRecordStockProduct($stockProductEntity);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     *
     * @return void
     */
    public function incrementStock($sku, $stockType, $incrementBy = 1)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $idProduct = $this->reader->getProductConcreteIdBySku($sku);
        $idStock = $this->reader->getStockTypeIdByName($stockType);

        $stockProductEntity = $this->queryContainer
            ->queryStockProductByStockAndProduct($idStock, $idProduct)
            ->findOneOrCreate();

        $stockProductEntity->increment($incrementBy);
        $this->insertActiveTouchRecordStockProduct($stockProductEntity);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockTypeEntity
     *
     * @return void
     */
    protected function insertActiveTouchRecordStockType(SpyStock $stockTypeEntity)
    {
        $this->touchFacade->touchActive(
            self::TOUCH_STOCK_TYPE,
            $stockTypeEntity->getIdStock()
        );
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct $stockProductEntity
     *
     * @return void
     */
    protected function insertActiveTouchRecordStockProduct(SpyStockProduct $stockProductEntity)
    {
        $this->touchFacade->touchActive(
            self::TOUCH_STOCK_PRODUCT,
            $stockProductEntity->getIdStockProduct()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     * @param int $idStockType
     * @param int $idProduct
     *
     * @return int
     */
    protected function saveStockProduct(StockProductTransfer $transferStockProduct, $idStockType, $idProduct)
    {
        $stockProduct = new SpyStockProduct();
        $stockProduct->setFkProduct($idProduct)
            ->setFkStock($idStockType)
            ->setIsNeverOutOfStock($transferStockProduct->getIsNeverOutOfStock())
            ->setQuantity($transferStockProduct->getQuantity())
            ->save();

        $this->insertActiveTouchRecordStockProduct($stockProduct);

        return $stockProduct->getPrimaryKey();
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function handleStockUpdatePlugins($sku)
    {
        foreach ($this->stockUpdateHandlerPlugins as $stockUpdateHandlerPlugin) {
            $stockUpdateHandlerPlugin->handle($sku);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistStockProductCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($productConcreteTransfer->getStocks() as $stockTransfer) {
            if (!$this->reader->hasStockProduct($stockTransfer->getSku(), $stockTransfer->getStockType())) {
                $this->createStockProduct($stockTransfer);
            } else {
                $idStockProduct = $stockTransfer->getIdStockProduct();
                if (!$idStockProduct) {
                    $idStockProduct = $this->reader->getIdStockProduct($stockTransfer->getSku(), $stockTransfer->getStockType());
                    $stockTransfer->setIdStockProduct($idStockProduct);
                }
                $this->updateStockProduct($stockTransfer);
            }
        }

        return $productConcreteTransfer;
    }
}
