<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipStorageToPriceProductServiceInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig as SharedPriceProductMerchantRelationshipStorageConfig;

class PriceProductMapper implements PriceProductMapperInterface
{
    protected const INDEX_SEPARATOR = '-';

    /**
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig
     */
    protected $priceProductMerchantRelationshipStorageConfig;

    /**
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipStorageToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig $priceProductMerchantRelationshipStorageConfig
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipStorageToPriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageConfig $priceProductMerchantRelationshipStorageConfig,
        PriceProductMerchantRelationshipStorageToPriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductMerchantRelationshipStorageConfig = $priceProductMerchantRelationshipStorageConfig;
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStorageTransferToPriceProductTransfers(
        PriceProductStorageTransfer $priceProductStorageTransfer
    ): array {
        $priceProductTransfers = [];

        foreach ($priceProductStorageTransfer->getPrices() as $idMerchantRelationship => $pricesPerMerchantRelationship) {
            foreach ($pricesPerMerchantRelationship as $currencyCode => $prices) {
                foreach (SharedPriceProductMerchantRelationshipStorageConfig::PRICE_MODES as $priceMode) {
                    if (!isset($prices[$priceMode])) {
                        continue;
                    }

                    foreach ($prices[$priceMode] as $priceType => $priceAmount) {
                        $priceProductTransfer = $this->findProductTransferInCollection(
                            $idMerchantRelationship,
                            $currencyCode,
                            $priceType,
                            $priceProductTransfers
                        );

                        $priceProductTransfer->setGroupKey($this->priceProductService->buildPriceProductGroupKey($priceProductTransfer))
                            ->setIsMergeable(true);

                        if ($priceMode === SharedPriceProductMerchantRelationshipStorageConfig::PRICE_GROSS_MODE) {
                            $priceProductTransfer->getMoneyValue()->setGrossAmount($priceAmount);
                            continue;
                        }

                        $priceProductTransfer->getMoneyValue()->setNetAmount($priceAmount);
                    }
                }
            }
        }

        return array_values($priceProductTransfers);
    }

    /**
     * @param int $idMerchantRelationship
     * @param string $currencyCode
     * @param string $priceType
     * @param array $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function findProductTransferInCollection(int $idMerchantRelationship, string $currencyCode, string $priceType, array &$priceProductTransfers): PriceProductTransfer
    {
        $index = implode(static::INDEX_SEPARATOR, [
            $idMerchantRelationship,
            $currencyCode,
            $priceType,
        ]);

        if (!isset($priceProductTransfers[$index])) {
            $priceProductTransfers[$index] = (new PriceProductTransfer())
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())
                        ->setType($this->priceProductMerchantRelationshipStorageConfig->getPriceDimensionMerchantRelationship())
                        ->setIdMerchantRelationship($idMerchantRelationship)
                )
                ->setMoneyValue(
                    (new MoneyValueTransfer())
                        ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                )
                ->setPriceTypeName($priceType);
        }

        return $priceProductTransfers[$index];
    }
}
