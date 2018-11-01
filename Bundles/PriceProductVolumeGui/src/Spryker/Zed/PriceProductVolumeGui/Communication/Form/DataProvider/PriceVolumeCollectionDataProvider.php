<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\PriceProductVolumeGui\Communication\Exception\PriceProductNotFoundException;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig;

class PriceVolumeCollectionDataProvider
{
    public const OPTION_CURRENCY_CODE = 'currency_code';
    public const OPTION_DIVISOR = 'divisor';
    public const OPTION_FRACTION_DIGITS = 'fraction_digits';

    protected const VOLUME_PRICES = 'volume_prices';

    protected const EMPTY_ROW_COUNT = 3;
    protected const FRACTION_POW_BASE = 10;
    protected const DEFAULT_FRACTION_DIGITS = 2;
    protected const DEFAULT_DIVISOR = 1;

    protected const MESSAGE_PRICE_PRODUCT_ABSTRACT_NOT_FOUND_ERROR = 'Price Product by chosen criteria is not defined for Product Abstract Id "%d".';
    protected const MESSAGE_PRICE_PRODUCT_CONCRETE_NOT_FOUND_ERROR = 'Price Product by chosen criteria is not defined for Product Concrete Id "%d".';

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig $config
     */
    public function __construct(
        PriceProductVolumeGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductVolumeGuiToCurrencyFacadeInterface $currencyFacade,
        PriceProductVolumeGuiToStoreFacadeInterface $storeFacade,
        PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService,
        PriceProductVolumeGuiConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $idProductAbstract
     * @param int|null $idProductConcrete
     *
     * @return array
     */
    public function getData(PriceProductTransfer $priceProductTransfer, int $idProductAbstract, ?int $idProductConcrete): array
    {
        $data = [];

        $data[PriceVolumeCollectionFormType::FIELD_ID_STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
        $data[PriceVolumeCollectionFormType::FIELD_ID_CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();
        $data[PriceVolumeCollectionFormType::FIELD_ID_PRODUCT_ABSTRACT] = $idProductAbstract;
        $data[PriceVolumeCollectionFormType::FIELD_ID_PRODUCT_CONCRETE] = $idProductConcrete;
        $data[PriceVolumeCollectionFormType::FIELD_NET_PRICE] = $priceProductTransfer->getMoneyValue()->getNetAmount();
        $data[PriceVolumeCollectionFormType::FIELD_GROSS_PRICE] = $priceProductTransfer->getMoneyValue()->getGrossAmount();
        $data[PriceVolumeCollectionFormType::FIELD_VOLUMES] = $this->getVolumes($priceProductTransfer);

        return $data;
    }

    /**
     * @param int $idProductAbstract
     * @param int|null $idProductConcrete
     * @param string $storeName
     * @param string $currencyCode
     * @param array $priceDimension
     *
     * @throws \Spryker\Zed\PriceProductVolumeGui\Communication\Exception\PriceProductNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(
        int $idProductAbstract,
        ?int $idProductConcrete,
        string $storeName,
        string $currencyCode,
        array $priceDimension
    ): PriceProductTransfer {
        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaTransfer($storeName, $currencyCode, $priceDimension);
        $priceProductTransfers = [];

        if ($idProductConcrete !== null) {
            $priceProductTransfers = $this->priceProductFacade
                ->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $idProductAbstract, $priceProductCriteriaTransfer);
        }

        if (empty($priceProductTransfers)) {
            $priceProductTransfers = $this->priceProductFacade
                ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);

            if (empty($priceProductTransfers)) {
                throw new PriceProductNotFoundException(
                    sprintf(static::MESSAGE_PRICE_PRODUCT_ABSTRACT_NOT_FOUND_ERROR, $idProductAbstract)
                );
            }
        }

        if (empty($priceProductTransfers)) {
            throw new PriceProductNotFoundException(
                sprintf(static::MESSAGE_PRICE_PRODUCT_CONCRETE_NOT_FOUND_ERROR, $idProductConcrete)
            );
        }

        return reset($priceProductTransfers);
    }

    /**
     * @param string $currencyCode
     *
     * @return array
     */
    public function getOptions(string $currencyCode): array
    {
        $currencyTransfer = $this->getCurrencyByCode($currencyCode);

        return [
            static::OPTION_CURRENCY_CODE => $currencyCode,
            static::OPTION_DIVISOR => $this->getDivisor($currencyTransfer),
            static::OPTION_FRACTION_DIGITS => $this->getFractionDigits($currencyTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function getVolumes(PriceProductTransfer $priceProductTransfer): array
    {
        $preSavedVolumes = $this->getPreSavedVolumes($priceProductTransfer);
        $emptyVolumes = $this->generateEmptyPriceProductVolumeItemTransfers(static::EMPTY_ROW_COUNT);

        return array_merge($preSavedVolumes, $emptyVolumes);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function getPreSavedVolumes(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProductVolumeItemTransfers = [];
        $volumePrices = $this->utilEncodingService->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);
        if (!$volumePrices || !$volumePrices[static::VOLUME_PRICES]) {
            return $priceProductVolumeItemTransfers;
        }

        foreach ($volumePrices[static::VOLUME_PRICES] as $volumePrice) {
            $priceProductVolumeItemTransfers[] = (new PriceProductVolumeItemTransfer())
                ->fromArray($volumePrice, true);
        }

        return $priceProductVolumeItemTransfers;
    }

    /**
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function generateEmptyPriceProductVolumeItemTransfers(int $quantity): array
    {
        $priceProductVolumeItemTransfers = [];
        for ($iterator = 1; $iterator <= $quantity; $iterator++) {
            $priceProductVolumeItemTransfers[] = new PriceProductVolumeItemTransfer();
        }

        return $priceProductVolumeItemTransfers;
    }

    /**
     * @param string $storeName
     * @param string $currencyCode
     * @param array $priceDimension
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function createPriceProductCriteriaTransfer(string $storeName, string $currencyCode, array $priceDimension): PriceProductCriteriaTransfer
    {
        $idCurrency = $this->getIdCurrencyByCode($currencyCode);
        $idStore = $this->getIdStoreByName($storeName);

        $priceProductDimensionTransfer = $this->createPriceProductDimensionTransfer($priceDimension);

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdCurrency($idCurrency)
            ->setIdStore($idStore)
            ->setPriceType($this->config->getPriceTypeDefaultName())
            ->setPriceDimension($priceProductDimensionTransfer);

        return $priceProductCriteriaTransfer;
    }

    /**
     * @param array $priceDimension
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function createPriceProductDimensionTransfer(array $priceDimension): PriceProductDimensionTransfer
    {
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->fromArray($priceDimension);

        if (!$priceProductDimensionTransfer->getType()) {
            $priceProductDimensionTransfer->setType($this->config->getPriceDimensionDefaultName());
        }

        return $priceProductDimensionTransfer;
    }

    /**
     * @param string $storeName
     *
     * @return int
     */
    protected function getIdStoreByName(string $storeName): int
    {
        $storeTransfer = $this->storeFacade->getStoreByName($storeName);

        return $storeTransfer->getIdStore();
    }

    /**
     * @param string $currencyCode
     *
     * @return int
     */
    protected function getIdCurrencyByCode(string $currencyCode): int
    {
        $currencyTransfer = $this->getCurrencyByCode($currencyCode);

        return $currencyTransfer->getIdCurrency();
    }

    /**
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyByCode(string $currencyCode): CurrencyTransfer
    {
        return $this->currencyFacade->fromIsoCode($currencyCode);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits) {
            return pow(static::FRACTION_POW_BASE, $fractionDigits);
        }

        return static::DEFAULT_DIVISOR;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getFractionDigits(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits) {
            return $fractionDigits;
        }

        return static::DEFAULT_FRACTION_DIGITS;
    }
}
