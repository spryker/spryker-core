<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;

class PriceProductConcreteReader implements PriceProductConcreteReaderInterface
{
    const COL_GROSS_PRICE = 'gross_price';
    const COL_NET_PRICE = 'net_price';

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
     */
    protected $priceProductQueryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductMapperInterface  $priceProductMapper
    ) {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param string $sku
     * @param PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    public function hasPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $productConcrete = $this->priceProductQueryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceProductCriteriaTransfer)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productConcrete !== null;
    }


    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesBySku($sku)
    {
        $productConcretePriceEntities = $this->priceProductQueryContainer
            ->queryPricesForProductConcreteBySku($sku)
            ->find();

        return $this->priceProductMapper->mapPriceProductTransferCollection($productConcretePriceEntities);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesById($idProductConcrete)
    {
        $productAbstractPriceEntities = $this->priceProductQueryContainer
            ->queryPricesForProductConcreteById($idProductConcrete)
            ->find();

        return $this->priceProductMapper->mapPriceProductTransferCollection($productAbstractPriceEntities);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array
     */
    public function getPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        return $this->priceProductQueryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceProductCriteriaTransfer)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, static::COL_GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, static::COL_NET_PRICE)
            ->setFormatter(ArrayFormatter::class)
            ->findOne();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int
     */
    public function findPriceProductId($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        return $this->priceProductQueryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceProductCriteriaTransfer)
            ->findOne()
            ->getIdPriceProduct();
    }
}
