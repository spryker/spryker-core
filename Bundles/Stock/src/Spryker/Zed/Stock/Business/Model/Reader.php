<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use InvalidArgumentException;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException;
use Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

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
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface $productFacade
     * @param \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface $transferMapper
     */
    public function __construct(
        StockQueryContainerInterface $queryContainer,
        StockToProductInterface $productFacade,
        StockProductTransferMapperInterface $transferMapper
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @return array
     */
    public function getStockTypes()
    {
        $types = [];
        $stockTypes = $this->queryContainer
            ->queryAllStockTypes()
            ->find();

        foreach ($stockTypes as $stockType) {
            $types[] = $stockType->getName();
        }

        return $types;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        $idProduct = $this->productFacade->findProductConcreteIdBySku($sku);
        $stock = $this->queryContainer->queryStockByNeverOutOfStockAllTypes($idProduct)->findOne();

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
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return array|\Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdProduct($idProductConcrete)
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

}
