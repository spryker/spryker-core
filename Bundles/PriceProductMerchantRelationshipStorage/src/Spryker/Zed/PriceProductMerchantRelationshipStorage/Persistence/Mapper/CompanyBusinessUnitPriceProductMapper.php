<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageSinglePriceTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface;

class CompanyBusinessUnitPriceProductMapper implements CompanyBusinessUnitPriceProductMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface
     */
    protected $priceKeyGenerator;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface $priceKeyGenerator
     */
    public function __construct(PriceKeyGeneratorInterface $priceKeyGenerator)
    {
        $this->priceKeyGenerator = $priceKeyGenerator;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $productStorePrices
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapProductAbstractPrices(array $productStorePrices): array
    {
        $pricesByCompanyBusinessUnit = [];
        foreach ($productStorePrices as $productStorePrice) {
            $productId = $productStorePrice->getPriceProduct()->getSpyProductAbstract()->getIdProductAbstract();
            $uniquePriceIndex = $this->createUniquePriceIndex($productStorePrice, $productId);
            if (!isset($pricesByCompanyBusinessUnit[$uniquePriceIndex])) {
                $pricesByCompanyBusinessUnit[$uniquePriceIndex] = $this->createPriceProductAbstractMerchantRelationshipStorageTransfer($productStorePrice, $uniquePriceIndex);
            }

            $this->addUngroupedPrice($pricesByCompanyBusinessUnit[$uniquePriceIndex], $productStorePrice);
        }

        return $pricesByCompanyBusinessUnit;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $productStorePrices
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapProductConcretePrices(array $productStorePrices): array
    {
        $pricesByCompanyBusinessUnit = [];
        foreach ($productStorePrices as $productStorePrice) {
            $productId = $productStorePrice->getPriceProduct()->getProduct()->getIdProduct();
            $uniquePriceIndex = $this->createUniquePriceIndex($productStorePrice, $productId);
            if (!isset($pricesByCompanyBusinessUnit[$uniquePriceIndex])) {
                $pricesByCompanyBusinessUnit[$uniquePriceIndex] = $this->createPriceProductConcreteMerchantRelationshipStorageTransfer($productStorePrice, $uniquePriceIndex);
            }

            $this->addUngroupedPrice($pricesByCompanyBusinessUnit[$uniquePriceIndex], $productStorePrice);
        }

        return $pricesByCompanyBusinessUnit;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $productStorePrice
     * @param string $uniquePriceIndex
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function createPriceProductConcreteMerchantRelationshipStorageTransfer(
        SpyPriceProductStore $productStorePrice,
        string $uniquePriceIndex
    ): PriceProductMerchantRelationshipStorageTransfer {
        $productSku = $productStorePrice->getPriceProduct()->getProduct()->getSku();
        $productId = $productStorePrice->getPriceProduct()->getProduct()->getIdProduct();

        return $this->createPriceProductMerchantRelationshipStorageTransfer(
            $productStorePrice,
            $uniquePriceIndex,
            $productSku,
            $productId
        );
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $productStorePrice
     * @param string $uniquePriceIndex
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function createPriceProductAbstractMerchantRelationshipStorageTransfer(
        SpyPriceProductStore $productStorePrice,
        string $uniquePriceIndex
    ): PriceProductMerchantRelationshipStorageTransfer {
        $productSku = $productStorePrice->getPriceProduct()->getSpyProductAbstract()->getSku();
        $productId = $productStorePrice->getPriceProduct()->getSpyProductAbstract()->getIdProductAbstract();

        return $this->createPriceProductMerchantRelationshipStorageTransfer(
            $productStorePrice,
            $uniquePriceIndex,
            $productSku,
            $productId
        );
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $productStorePrice
     * @param string $uniquePriceIndex
     * @param string $productSku
     * @param int $productId
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function createPriceProductMerchantRelationshipStorageTransfer(
        SpyPriceProductStore $productStorePrice,
        string $uniquePriceIndex,
        string $productSku,
        int $productId
    ): PriceProductMerchantRelationshipStorageTransfer {
        $storeName = $productStorePrice->getStore()->getName();

        $idCompanyBusinessUnit = $productStorePrice->getVirtualColumn(PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT);

        return (new PriceProductMerchantRelationshipStorageTransfer())
            ->setPriceKey($uniquePriceIndex)
            ->setIdProduct($productId)
            ->setSku($productSku)
            ->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setStoreName($storeName);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $productStorePrice
     * @param int $productId
     *
     * @return string
     */
    protected function createUniquePriceIndex(SpyPriceProductStore $productStorePrice, int $productId): string
    {
        return $this->priceKeyGenerator
            ->buildPriceKey(
                $productStorePrice->getStore()->getName(),
                $productId,
                $productStorePrice->getVirtualColumn(PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     *
     * @return void
     */
    protected function addUngroupedPrice(
        PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer,
        SpyPriceProductStore $priceProductStoreEntity
    ): void {
        $priceType = $priceProductStoreEntity->getPriceProduct()->getPriceType()->getName();
        $currencyCode = $priceProductStoreEntity->getCurrency()->getCode();
        $idMerchantRelationship = $priceProductStoreEntity->getVirtualColumn(PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP);

        $merchantRelationshipStorageTransfer->addUngroupedPrice(
            (new PriceProductMerchantRelationshipStorageSinglePriceTransfer())
                ->setPriceType($priceType)
                ->setGrossPrice($priceProductStoreEntity->getGrossPrice())
                ->setNetPrice($priceProductStoreEntity->getNetPrice())
                ->setCurrencyCode($currencyCode)
                ->setIdMerchantRelationship($idMerchantRelationship)
        );
    }
}
