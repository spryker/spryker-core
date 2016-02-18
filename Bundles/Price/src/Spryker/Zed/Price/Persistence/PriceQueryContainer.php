<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;

/**
 * @method \Spryker\Zed\Price\Persistence\PricePersistenceFactory getFactory()
 */
class PriceQueryContainer extends AbstractQueryContainer implements PriceQueryContainerInterface
{

    const DATE_NOW = 'now';

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name)
    {
        return $this->getFactory()->createPriceTypeQuery()->filterByName($name);
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes()
    {
        return $this->getFactory()->createPriceTypeQuery();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPriceType($priceType)
            ->useSpyProductAbstractQuery()
            ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductAbstract(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useSpyProductAbstractQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductConcrete(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByIdPriceProduct($idPriceProduct);
    }

}
