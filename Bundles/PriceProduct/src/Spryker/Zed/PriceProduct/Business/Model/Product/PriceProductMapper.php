<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductMapper implements PriceProductMapperInterface
{
    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    protected $priceProductTypeMapper;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface $priceProductTypeMapper
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(
        PriceProductToCurrencyFacadeInterface $currencyFacade,
        ProductPriceTypeMapperInterface $priceProductTypeMapper,
        PriceProductToPriceFacadeInterface $priceFacade,
        PriceProductConfig $config
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceProductTypeMapper = $priceProductTypeMapper;
        $this->priceFacade = $priceFacade;
        $this->config = $config;
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
            ->setIdProduct($priceProductEntity->getFkProduct())
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStoreEntityTransfersToPriceProduct(
        array $priceProductStoreTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $productPriceCollection = [];
        foreach ($priceProductStoreTransferCollection as $priceProductStoreEntityTransfer) {
            $index = $this->buildCollectionIndex($priceProductStoreEntityTransfer);
            $productPriceCollection[$index] = $this->mapPriceProductTransfer($priceProductStoreEntityTransfer, $priceProductCriteriaTransfer);
        }

        return $productPriceCollection;
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
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
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
     *
     * @return string
     */
    protected function buildCollectionIndex(SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer): string
    {
        $priceType = $this->config->getPriceTypeDefaultName();
        $priceConfigurationMode = $this->config->getPriceModeIdentifierForBothType();

        return implode(
            '-',
            [
                $priceProductStoreEntityTransfer->getFkStore(),
                $priceProductStoreEntityTransfer->getFkCurrency(),
                $priceType,
                $priceConfigurationMode,
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductTransfer(
        SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductTransfer {

        $priceProductEntityTransfer = $priceProductStoreEntityTransfer->getPriceProduct();

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setName($this->config->getPriceTypeDefaultName())
            ->setPriceModeConfiguration($this->config->getPriceModeIdentifierForBothType());

        $moneyValueTransfer = $this->getMoneyValueTransfer($priceProductStoreEntityTransfer);

        $priceProductDimensionTransfer = $this->getPriceProductDimensionTransfer(
            $priceProductCriteriaTransfer,
            $priceProductStoreEntityTransfer
        );
        return (new PriceProductTransfer())
            ->setIdProduct($priceProductEntityTransfer->getFkProduct())
            ->setIdProductAbstract($priceProductEntityTransfer->getFkProductAbstract())
            ->setIdProduct($priceProductEntityTransfer->getIdPriceProduct())
            ->setPriceType($priceTypeTransfer)
            ->setPriceTypeName($this->config->getPriceTypeDefaultName())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function getMoneyValueTransfer(SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer): MoneyValueTransfer
    {
        $currencyTransfer = $this->currencyFacade->getByIdCurrency(
            $priceProductStoreEntityTransfer->getFkCurrency()
        );

        return (new MoneyValueTransfer())
            ->setCurrency($currencyTransfer)
            ->setIdEntity($priceProductStoreEntityTransfer->getIdPriceProductStore())
            ->setFkCurrency($priceProductStoreEntityTransfer->getFkCurrency())
            ->setFkStore($priceProductStoreEntityTransfer->getFkStore())
            ->setNetAmount($priceProductStoreEntityTransfer->getNetPrice())
            ->setGrossAmount($priceProductStoreEntityTransfer->getGrossPrice());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getPriceProductDimensionTransfer(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
    ): PriceProductDimensionTransfer {
        return (new PriceProductDimensionTransfer())
            ->setType($priceProductCriteriaTransfer->getPriceDimension())
            ->fromArray(
                $priceProductStoreEntityTransfer->virtualProperties(),
                true
            );
    }
}
