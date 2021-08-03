<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface;

class PriceProductsVolumeDataExpander implements PriceProductsVolumeDataExpanderInterface
{
    protected const REQUEST_DATA_KEY_VOLUME_QUANTITY = 'volume_quantity';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper
     */
    protected $priceProductOfferMapper;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
     */
    protected $priceProductOfferVolumeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface
     */
    protected $priceProductOfferDataProvider;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper $priceProductOfferMapper
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface $priceProductOfferVolumeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface $priceProductFilter
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface $priceProductOfferDataProvider
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        PriceProductOfferMapper $priceProductOfferMapper,
        ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface $priceProductOfferVolumeFacade,
        PriceProductFilterInterface $priceProductFilter,
        PriceProductOfferDataProviderInterface $priceProductOfferDataProvider
    ) {
        $this->priceProductVolumeService = $priceProductVolumeService;
        $this->priceProductOfferMapper = $priceProductOfferMapper;
        $this->priceProductOfferVolumeFacade = $priceProductOfferVolumeFacade;
        $this->priceProductFilter = $priceProductFilter;
        $this->priceProductOfferDataProvider = $priceProductOfferDataProvider;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param mixed[] $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductsWithVolumeData(
        ArrayObject $priceProductTransfers,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject {
        $storedPriceProductTransfers = $this->priceProductOfferDataProvider->getPriceProductTransfers($idProductOffer);

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $storedPriceProductTransfers = $this->expandStoredPriceProductsWithVolumeData(
                $priceProductTransfer,
                $storedPriceProductTransfers,
                $requestData,
                $volumeQuantity
            );
        }

        return $storedPriceProductTransfers;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $storedPriceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $storedPriceProductTransfers
     * @param mixed[] $requestData
     * @param int $volumeQuantity
     *
     * @return \ArrayObject
     */
    protected function expandStoredPriceProductsWithVolumeData(
        PriceProductTransfer $priceProductTransfer,
        ArrayObject $storedPriceProductTransfers,
        array $requestData,
        int $volumeQuantity
    ): ArrayObject {
        $filteredPriceProductTransfers = array_values($this->priceProductFilter->filterPriceProductTransfers(
            $storedPriceProductTransfers->getArrayCopy(),
            (new PriceProductOfferCriteriaTransfer())
                ->addIdCurrency($priceProductTransfer->getMoneyValueOrFail()->getFkCurrencyOrFail())
                ->addIdStore($priceProductTransfer->getMoneyValueOrFail()->getFkStoreOrFail())
                ->addIdPriceType($priceProductTransfer->getPriceTypeOrFail()->getIdPriceTypeOrFail())
        ));

        /** @var \Generated\Shared\Transfer\PriceProductTransfer|null $storedPriceProductTransfer */
        $storedPriceProductTransfer = $filteredPriceProductTransfers[0] ?? null;

        foreach ($requestData as $key => $value) {
            if ($storedPriceProductTransfer) {
                $storedPriceProductTransfers = $this->expandStoredPriceProductTransfers(
                    $priceProductTransfer,
                    $storedPriceProductTransfer,
                    $storedPriceProductTransfers,
                    $volumeQuantity,
                    $key,
                    $value
                );

                continue;
            }

            $storedPriceProductTransfers = $this->addPriceProductWithVolumePrice(
                $priceProductTransfer,
                $storedPriceProductTransfers,
                $volumeQuantity,
                $key,
                $value
            );
        }

        return $storedPriceProductTransfers;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $storedPriceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $storedPriceProductTransfers
     * @param int $volumeQuantity
     * @param string $requestKey
     * @param string $requestValue
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addPriceProductWithVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        ArrayObject $storedPriceProductTransfers,
        int $volumeQuantity,
        string $requestKey,
        string $requestValue
    ): ArrayObject {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $netAmount = $moneyValueTransfer->getNetAmount();
        $grossAmount = $moneyValueTransfer->getGrossAmount();
        $moneyValueTransfer->setNetAmount(null)->setGrossAmount(null);
        $priceProductTransfer
            ->setVolumeQuantity((int)$volumeQuantity)
            ->setIdPriceProduct(null)
            ->setMoneyValue($moneyValueTransfer);
        $volumeQuantity = $this->getVolumeQuantity($volumeQuantity, $requestKey, $requestValue);

        $storedPriceProductTransfers->append(
            $this->priceProductVolumeService->addVolumePrice(
                $priceProductTransfer,
                (new PriceProductTransfer())->setVolumeQuantity($volumeQuantity)->setMoneyValue(
                    (new MoneyValueTransfer())->setNetAmount($netAmount)->setGrossAmount($grossAmount)
                )
            )
        );

        return $storedPriceProductTransfers;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $storedPriceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $storedPriceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $storedPriceProductTransfers
     * @param int $volumeQuantity
     * @param string $requestKey
     * @param string $requestValue
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function expandStoredPriceProductTransfers(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $storedPriceProductTransfer,
        ArrayObject $storedPriceProductTransfers,
        int $volumeQuantity,
        string $requestKey,
        string $requestValue
    ): ArrayObject {
        if ($requestKey === MoneyValueTransfer::STORE || $requestKey === MoneyValueTransfer::CURRENCY) {
            return $this->expandStoredPriceProductTransfersWithStoreAndCurrency(
                $priceProductTransfer,
                $storedPriceProductTransfer,
                $storedPriceProductTransfers,
                $volumeQuantity,
                $requestKey,
                $requestValue
            );
        }

        $priceProductTransferToReplace = (new PriceProductTransfer())->setMoneyValue(new MoneyValueTransfer());
        $priceProductTransferToReplace->setVolumeQuantity(
            $this->getVolumeQuantity($volumeQuantity, $requestKey, $requestValue)
        );
        $priceProductTransferToReplace = $this->priceProductVolumeService->extractVolumePrice(
            $storedPriceProductTransfer,
            $priceProductTransferToReplace
        );

        if ($priceProductTransferToReplace !== null) {
            $priceProductTransferToReplace = $this->priceProductOfferMapper->mapMoneyValuesToPriceProductTransfer(
                $requestKey,
                $requestValue,
                $priceProductTransferToReplace
            );

            $this->priceProductVolumeService->deleteVolumePrice(
                $storedPriceProductTransfer,
                (new PriceProductTransfer())->setVolumeQuantity((int)$volumeQuantity)
            );
            $this->priceProductVolumeService->addVolumePrice($storedPriceProductTransfer, $priceProductTransferToReplace);
        }

        return $storedPriceProductTransfers;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $storedPriceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $storedPriceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $storedPriceProductTransfers
     * @param int $volumeQuantity
     * @param string $requestKey
     * @param string $requestValue
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function expandStoredPriceProductTransfersWithStoreAndCurrency(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $storedPriceProductTransfer,
        ArrayObject $storedPriceProductTransfers,
        int $volumeQuantity,
        string $requestKey,
        string $requestValue
    ): ArrayObject {
        $idCurrency = $requestKey === MoneyValueTransfer::CURRENCY ? (int)$requestValue : $priceProductTransfer->getMoneyValueOrFail()->getFkCurrencyOrFail();
        $idStore = $requestKey === MoneyValueTransfer::STORE ? (int)$requestValue : $priceProductTransfer->getMoneyValueOrFail()->getFkStoreOrFail();
        $filteredPriceProductTransfers = array_values($this->priceProductFilter->filterPriceProductTransfers(
            $storedPriceProductTransfers->getArrayCopy(),
            (new PriceProductOfferCriteriaTransfer())
                ->addIdCurrency($idCurrency)
                ->addIdStore($idStore)
                ->addIdPriceType($priceProductTransfer->getPriceTypeOrFail()->getIdPriceTypeOrFail())
        ));

        /** @var \Generated\Shared\Transfer\PriceProductTransfer|null $filteredPriceProductTransfer */
        $filteredPriceProductTransfer = $filteredPriceProductTransfers[0] ?? null;

        if ($filteredPriceProductTransfer) {
            $priceProductVolumeTransfers = $this->priceProductOfferVolumeFacade->extractVolumePrices([$storedPriceProductTransfer]);

            $this->priceProductVolumeService->deleteVolumePrice(
                $storedPriceProductTransfer,
                (new PriceProductTransfer())->setVolumeQuantity($volumeQuantity)
            );

            foreach ($priceProductVolumeTransfers as $priceProductVolumeTransfer) {
                if ($priceProductVolumeTransfer->getVolumeQuantityOrFail() === $volumeQuantity) {
                    $this->priceProductVolumeService->addVolumePrice(
                        $filteredPriceProductTransfer,
                        $priceProductVolumeTransfer
                    );
                    $filteredPriceProductTransfer->setVolumeQuantity($priceProductVolumeTransfer->getVolumeQuantityOrFail());
                }
            }

            return $storedPriceProductTransfers;
        }

        $storedPriceProductTransfers = $this->addPriceProductWithVolumePrice(
            $priceProductTransfer,
            $storedPriceProductTransfers,
            $volumeQuantity,
            $requestKey,
            $requestValue
        );

        return $storedPriceProductTransfers;
    }

    /**
     * @param int $volumeQuantity
     * @param string $requestKey
     * @param string $requestValue
     *
     * @return int
     */
    protected function getVolumeQuantity(int $volumeQuantity, string $requestKey, string $requestValue): int
    {
        return strpos($requestKey, static::REQUEST_DATA_KEY_VOLUME_QUANTITY) === false ? $volumeQuantity : (int)$requestValue;
    }
}
