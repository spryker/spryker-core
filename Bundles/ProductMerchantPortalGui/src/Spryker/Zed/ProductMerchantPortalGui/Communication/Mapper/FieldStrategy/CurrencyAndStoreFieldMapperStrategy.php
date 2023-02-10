<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class CurrencyAndStoreFieldMapperStrategy extends AbstractFieldMapperStrategy
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        parent::__construct($priceProductFacade);

        $this->priceProductVolumeService = $priceProductVolumeService;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param string $dataField
     *
     * @return bool
     */
    public function isApplicable(string $dataField): bool
    {
        return $dataField === MoneyValueTransfer::CURRENCY || $dataField === MoneyValueTransfer::STORE;
    }

    /**
     * @param array<string, mixed> $data
     * @param int $volumeQuantity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapDataToPriceProductTransfers(
        array $data,
        int $volumeQuantity,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $priceProductTransfers = $this->expandPriceProductTransfersWithTypes($priceProductTransfers);

        if ($volumeQuantity > 1) {
            $priceProductTransfer = $this->findDefaultPriceProduct($priceProductTransfers);

            if ($priceProductTransfer === null) {
                return $priceProductTransfers;
            }

            $newPriceProductTransfer = $this->findOrCreatePriceProduct($data, $priceProductTransfer);
            $newPriceProductTransfer = $this->moveVolumePriceToNewPriceProduct(
                $newPriceProductTransfer,
                $volumeQuantity,
                $priceProductTransfer,
            );

            $priceProductTransfers->append($newPriceProductTransfer);

            return $priceProductTransfers;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->mapDataToMoneyValueTransfer($data, $priceProductTransfer->getMoneyValueOrFail());
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function findOrCreatePriceProduct(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $newPriceProductTransfers = $this->getPriceProducts($priceProductTransfer, $data);

        if (count($newPriceProductTransfers) === 0) {
            $newPriceProductTransfer = $this->createNewPriceProduct(
                $priceProductTransfer->getPriceTypeOrFail()->getNameOrFail(),
                $priceProductTransfer,
            );

            $this->mapDataToMoneyValueTransfer(
                $data,
                $newPriceProductTransfer->getMoneyValueOrFail(),
            );

            return $newPriceProductTransfer;
        }

        return $newPriceProductTransfers[0];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array<string, mixed> $data
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPriceProducts(PriceProductTransfer $priceProductTransfer, array $data): array
    {
        $idProductConcrete = $priceProductTransfer->getIdProduct();
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setIdProductConcrete($idProductConcrete)
            ->setIdCurrency($data[MoneyValueTransfer::CURRENCY] ?? $moneyValueTransfer->getFkCurrency())
            ->setIdStore($data[MoneyValueTransfer::STORE] ?? $moneyValueTransfer->getFkStore())
            ->setPriceType($priceProductTransfer->getPriceTypeOrFail()->getNameOrFail())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductConfig::PRICE_DIMENSION_DEFAULT),
            );

        if ($idProductConcrete && $idProductAbstract) {
            return $this->priceProductFacade
                ->findProductConcretePricesWithoutPriceExtraction(
                    $idProductConcrete,
                    $idProductAbstract,
                    $priceProductCriteriaTransfer,
                );
        }

        if ($idProductAbstract) {
            return $this->priceProductFacade
                ->findProductAbstractPricesWithoutPriceExtraction(
                    $idProductAbstract,
                    $priceProductCriteriaTransfer,
                );
        }

        return [];
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapDataToMoneyValueTransfer(
        array $data,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $key = key($data);
        $value = $data[$key];

        if ($key === MoneyValueTransfer::STORE) {
            $value = (int)$value;
            $moneyValueTransfer->setFkStore($value);
            $moneyValueTransfer->setStore(
                (new StoreTransfer())
                    ->setIdStore($value),
            );

            return $moneyValueTransfer;
        }

        if ($key === MoneyValueTransfer::CURRENCY) {
            $value = (int)$value;
            $moneyValueTransfer->setFkCurrency($value);
            $moneyValueTransfer->setCurrency(
                $this->currencyFacade->getByIdCurrency($value),
            );

            return $moneyValueTransfer;
        }

        return $moneyValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param int $volumeQuantity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function moveVolumePriceToNewPriceProduct(
        PriceProductTransfer $newPriceProductTransfer,
        int $volumeQuantity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $volumePriceProductTransfer = (new PriceProductTransfer())
            ->setVolumeQuantity($volumeQuantity);
        $volumePriceProductTransfer = $this->priceProductVolumeService
            ->extractVolumePrice($priceProductTransfer, $volumePriceProductTransfer);

        if (!$volumePriceProductTransfer) {
            return $newPriceProductTransfer;
        }

        $newPriceProductTransfer = $this->priceProductVolumeService
            ->addVolumePrice($newPriceProductTransfer, $volumePriceProductTransfer);
        $this->priceProductVolumeService
            ->deleteVolumePrice($priceProductTransfer, $volumePriceProductTransfer);

        return $newPriceProductTransfer;
    }
}
