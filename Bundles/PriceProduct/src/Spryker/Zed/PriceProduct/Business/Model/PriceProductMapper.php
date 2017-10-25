<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface;

class PriceProductMapper implements PriceProductMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    protected $priceProductTypeMapper;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface $priceProductTypeMapper
     */
    public function __construct(
        PriceProductToCurrencyInterface $currencyFacade,
        ProductPriceTypeMapperInterface $priceProductTypeMapper
    )
    {
        $this->currencyFacade = $currencyFacade;
        $this->priceProductTypeMapper = $priceProductTypeMapper;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapProductPriceTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyPriceProduct $priceProductEntity
    ) {

        $moneyValueTransfer = $this->mapMoneyValueTransfer($priceProductStoreEntity);
        $priceTypeTransfer = $this->priceProductTypeMapper->mapFromEntity($priceProductEntity->getPriceType());

        return (new PriceProductTransfer())
            ->fromArray($priceProductEntity->toArray(), true)
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct[] $priceProductEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductTransferCollection($priceProductEntities)
    {
        $productPriceCollection = [];
        foreach ($priceProductEntities as $priceProductEntity) {
            foreach ($priceProductEntity->getPriceProductStores() as $priceProductStoreEntity) {
                $index = $this->createProductPriceGroupingIndex($priceProductStoreEntity, $priceProductEntity);
                $productPriceCollection[$index] = $this->mapProductPriceTransfer(
                    $priceProductStoreEntity,
                    $priceProductEntity
                );
            }
        }

        return $productPriceCollection;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyValueTransfer(SpyPriceProductStore $priceProductStoreEntity)
    {
        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductStoreEntity->getFkCurrency());

        return (new MoneyValueTransfer())
            ->fromArray($priceProductStoreEntity->toArray(), true)
            ->setIdEntity($priceProductStoreEntity->getPrimaryKey())
            ->setNetAmount($priceProductStoreEntity->getNetPrice())
            ->setGrossAmount($priceProductStoreEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);
    }


    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return string
     */
    protected function createProductPriceGroupingIndex(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyPriceProduct $priceProductEntity
    ) {
        return implode(
            '-',
            [
                $priceProductStoreEntity->getFkStore(),
                $priceProductStoreEntity->getFkCurrency(),
                $priceProductEntity->getPriceType()->getName(),
                $priceProductEntity->getPriceType()->getPriceModeConfiguration(),
            ]
        );
    }
}
