<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Business\Model;

use Guzzle\Common\Exception\InvalidArgumentException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use SprykerFeature\Zed\Stock\Business\Exception\StockProductNotFoundException;
use SprykerFeature\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use SprykerFeature\Zed\Stock\Persistence\StockQueryContainer;

class Reader implements ReaderInterface
{

    const MESSAGE_NO_RESULT = 'no stock set for this sku';
    const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

    /**
     * @var StockQueryContainer
     */
    protected $queryContainer;

    /**
     * @var StockToProductInterface
     */
    protected $productFacade;

    /**
     * @param StockQueryContainer $queryContainer
     * @param StockToProductInterface $productFacade
     */
    public function __construct(
        StockQueryContainer $queryContainer,
        StockToProductInterface $productFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @return array
     */
    public function getStockTypes()
    {
        $types = [];
        $stockTypes = $this->queryContainer->queryAllStockTypes()->find();
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
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);
        $stock = $this->queryContainer->queryStockByNeverOutOfStockAllTypes($idProduct)->findOne();

        return ($stock !== null);
    }

    /**
     * @param string $sku
     *
     * @throws InvalidArgumentException
     *
     * @return SpyStockProduct[]
     */
    public function getStocksProduct($sku)
    {
        $productId = $this->productFacade->getConcreteProductIdBySku($sku);
        $stockEntities = $this->queryContainer
            ->queryStockByProducts($productId)
            ->find()
        ;
        if (count($stockEntities) < 1) {
            throw new InvalidArgumentException(self::MESSAGE_NO_RESULT);
        } else {
            return $stockEntities;
        }
    }

    /**
     * @param string $stockType
     *
     * @throws InvalidArgumentException
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
        $entityCount = $this->queryContainer->queryStockProductBySkuAndType($sku, $stockType)->count();

        return $entityCount > 0;
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @throws StockProductNotFoundException
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        $idStockType = $this->getStockTypeIdByName($stockType);
        $idProduct = $this->getConcreteProductIdBySku($sku);
        $stockProductEntity = $this->queryContainer
            ->queryStockProductByStockAndProduct($idStockType, $idProduct)
            ->findOne()
        ;

        if ($stockProductEntity) {
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
     * @throws StockProductAlreadyExistsException
     */
    public function checkStockDoesNotExist($idStockType, $idProduct)
    {
        $stockProductQuery = $this->queryContainer->queryStockProductByStockAndProduct($idStockType, $idProduct);

        if ($stockProductQuery->count() > 0) {
            throw new StockProductAlreadyExistsException(
                'Cannot duplicate entry: this stock type is already set for this product'
            );
        }
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku)
    {
        return $this->productFacade->getAbstractProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->productFacade->getConcreteProductIdBySku($sku);
    }

    /**
     * @param int $idStockProduct
     *
     * @throws StockProductNotFoundException
     *
     * @return SpyStockProduct
     */
    public function getStockProductById($idStockProduct)
    {
        $stockProductEntity = $this->queryContainer
            ->queryStockProductByIdStockProduct($idStockProduct)
            ->findOne()
        ;

        if ($stockProductEntity === null) {
            throw new StockProductNotFoundException();
        }

        return $stockProductEntity;
    }

    /**
     * @param string $stockType
     *
     * @return bool
     */
    protected function hasStockType($stockType)
    {
        $stockTypeCount = $this->queryContainer->queryStockByName($stockType)->count();

        return $stockTypeCount > 0;
    }

}
