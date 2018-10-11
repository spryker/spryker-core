<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig;

class PriceVolumeCollectionDataProvider
{
    public const PRICE_TYPE_DEFAULT = 'DEFAULT';

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
     *
     * @return array
     */
    public function getData(PriceProductTransfer $priceProductTransfer): array
    {
        $data[PriceVolumeCollectionFormType::FIELD_ID_STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
        $data[PriceVolumeCollectionFormType::FIELD_ID_CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();

        $volumes = [];

        $preSavedVolumes = $this->utilEncodingService->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData());

        if ($preSavedVolumes && $preSavedVolumes->volume_prices) {
            foreach ($preSavedVolumes->volume_prices as $preSavedVolume) {
                $volumes[] = (new PriceProductVolumeItemTransfer())
                    ->fromArray(get_object_vars($preSavedVolume), true);
            }
        }

        $volumes = array_merge($volumes, [
            new PriceProductVolumeItemTransfer(),
            new PriceProductVolumeItemTransfer(),
            new PriceProductVolumeItemTransfer(),
        ]);

        $data[PriceVolumeCollectionFormType::FIELD_VOLUMES] = $volumes;

        return $data;
    }

    /**
     * @param int $idProductAbstract
     * @param int|null $idProductConcrete
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(int $idProductAbstract, ?int $idProductConcrete, string $storeName, string $currencyCode): PriceProductTransfer
    {
        $idCurrency = $this->findIdCurrency($currencyCode);
        $idStore = $this->findIdStore($storeName);

        $priceProductDimensionTransfer = new PriceProductDimensionTransfer();
        $priceProductDimensionTransfer->setType($this->config->getPriceDimensionDefaultName());

        $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setIdCurrency($idCurrency)
            ->setIdStore($idStore)
            ->setPriceType($this->config->getPriceTypeDefaultName())
        ->setPriceDimension($priceProductDimensionTransfer);

        $priceProductTransfers = [];

        if ($idProductConcrete) {
            $priceProductTransfers = $this->priceProductFacade
                ->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $idProductAbstract, $priceProductCriteriaTransfer);
        }

        if (!$priceProductTransfers) {
            $priceProductTransfers = $this->priceProductFacade
                ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) { //todo: check `findProductAbstractPricesWithoutPriceExtraction` filtering by PriceType.
            if ($priceProductTransfer->getPriceTypeName() == $this->config->getPriceTypeDefaultName()) {
                return $priceProductTransfer;
            }
        }

        // todo: throw exception
    }

    /**
     * @param string $storeName
     *
     * @return int|null
     */
    protected function findIdStore(string $storeName): ?int
    {
        $storeTransfer = $this->storeFacade->getStoreByName($storeName);

        return $storeTransfer->getIdStore();
    }

    /**
     * @param string $currencyCode
     *
     * @return int|null
     */
    protected function findIdCurrency(string $currencyCode): ?int
    {
        $currencyTransfer = $this->currencyFacade->fromIsoCode($currencyCode);

        return $currencyTransfer->getIdCurrency();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }
}
