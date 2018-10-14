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
     *
     * @throws \Spryker\Zed\PriceProductVolumeGui\Communication\Exception\PriceProductNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(int $idProductAbstract, ?int $idProductConcrete, string $storeName, string $currencyCode): PriceProductTransfer
    {
        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaTransfer($storeName, $currencyCode);
        $priceProductTransfers = [];

        if ($idProductConcrete) {
            $priceProductTransfers = $this->priceProductFacade
                ->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $idProductAbstract, $priceProductCriteriaTransfer);
        }

        if (!$priceProductTransfers) {
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
        return [
            static::OPTION_CURRENCY_CODE => $currencyCode,
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
        $emptyVolumes = $this->generateEmptyPriceProductVolumeItemTransfers($this->config->getEmptyRowsQuantity());

        return array_merge($preSavedVolumes, $emptyVolumes);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function getPreSavedVolumes(PriceProductTransfer $priceProductTransfer): array
    {
        $volumes = [];
        $preSavedVolumes = $this->utilEncodingService->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData());
        if ($preSavedVolumes && $preSavedVolumes->volume_prices) {
            foreach ($preSavedVolumes->volume_prices as $preSavedVolume) {
                $volumes[] = (new PriceProductVolumeItemTransfer())
                    ->fromArray(get_object_vars($preSavedVolume), true);
            }
        }

        return $volumes;
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
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function createPriceProductCriteriaTransfer(string $storeName, string $currencyCode): PriceProductCriteriaTransfer
    {
        $idCurrency = $this->findIdCurrency($currencyCode);
        $idStore = $this->findIdStore($storeName);

        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($this->config->getPriceDimensionDefaultName());

        $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer
            ->setIdCurrency($idCurrency)
            ->setIdStore($idStore)
            ->setPriceType($this->config->getPriceTypeDefaultName())
            ->setPriceDimension($priceProductDimensionTransfer);

        return $priceProductCriteriaTransfer;
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
}
