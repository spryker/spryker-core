<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleFormDataProvider
{
    public const OPTION_DATA_CLASS = 'data_class';
    public const OPTION_STORE_CHOICES = 'option_store_choices';
    public const OPTION_CURRENCY_CHOICES = 'option_currency_choices';
    public const OPTION_PRICE_TYPE_CHOICES = 'option_price_type_choices';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    protected $priceProductScheduleTransfer;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer|null $priceProductScheduleTransfer
     */
    public function __construct(
        PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade,
        ?PriceProductScheduleTransfer $priceProductScheduleTransfer
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
        $this->priceProductScheduleTransfer = $priceProductScheduleTransfer;
    }

    /**
     * @return array
     */
    protected function getPriceTypeValues(): array
    {
        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        $result = [];

        foreach ($priceTypes as $priceType) {
            $result[$priceType->getIdPriceType()] = $priceType->getName();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getStoreValues(): array
    {
        $storeCollection = $this->storeFacade->getAllStores();
        $result = [];

        foreach ($storeCollection as $storeTransfer) {
            $result[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $result;
    }

    /**
     * @param int|null $idStore
     *
     * @return array
     */
    protected function getCurrencyValues(?int $idStore): array
    {
        if ($idStore === null) {
            return [];
        }

        $result = [];
        $storeWithCurrenciesTransfer = $this->currencyFacade->getStoreWithCurrenciesByIdStore($idStore);

        foreach ($storeWithCurrenciesTransfer->getCurrencies() as $currencyTransfer) {
            $result[$currencyTransfer->getIdCurrency()] = $currencyTransfer->getCode();
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function getData(): PriceProductScheduleTransfer
    {
        if ($this->priceProductScheduleTransfer === null) {
            return new PriceProductScheduleTransfer();
        }

        return $this->priceProductScheduleTransfer;
    }

    /**
     * @param int|null $idStore
     *
     * @return array
     */
    public function getOptions(?int $idStore = null): array
    {
        return [
            static::OPTION_DATA_CLASS => PriceProductScheduleTransfer::class,
            static::OPTION_STORE_CHOICES => $this->getStoreValues(),
            static::OPTION_CURRENCY_CHOICES => $this->getCurrencyValues($idStore),
            static::OPTION_PRICE_TYPE_CHOICES => $this->getPriceTypeValues(),
        ];
    }
}
