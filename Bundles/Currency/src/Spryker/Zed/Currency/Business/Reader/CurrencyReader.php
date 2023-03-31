<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface;

class CurrencyReader implements CurrencyReaderInterface
{
    /**
     * @var \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface
     */
    protected $currencyRepository;

    /**
     * @var array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected static $currencyCache = [];

    /**
     * @param \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface $currencyRepository
     */
    public function __construct(
        CurrencyToStoreFacadeInterface $storeFacade,
        CurrencyRepositoryInterface $currencyRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param int $idCurrency
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIdCurrency(int $idCurrency): CurrencyTransfer
    {
        if (isset(static::$currencyCache[$idCurrency])) {
            return static::$currencyCache[$idCurrency];
        }

        $currencyTransfer = $this->currencyRepository->findCurrencyById($idCurrency);

        if ($currencyTransfer === null) {
            throw new CurrencyNotFoundException(
                sprintf('Currency with id "%d" not found.', $idCurrency),
            );
        }

        static::$currencyCache[$idCurrency] = $currencyTransfer;

        return $currencyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getCurrentStoreWithCurrencies(): StoreWithCurrencyTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->mapStoreCurrency($storeTransfer);
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer>
     */
    public function getAllStoresWithCurrencies(): array
    {
        $currenciesPerStore = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getAvailableCurrencyIsoCodes() === []) {
                continue;
            }
            $currenciesPerStore[] = $this->mapStoreCurrency($storeTransfer);
        }

        return $currenciesPerStore;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getStoreWithCurrenciesByIdStore(int $idStore): StoreWithCurrencyTransfer
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        return $this->mapStoreCurrency($storeTransfer);
    }

    /**
     * @param string $isoCode
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIsoCode(string $isoCode): CurrencyTransfer
    {
        if (isset(static::$currencyCache[$isoCode])) {
            return static::$currencyCache[$isoCode];
        }

        $currencyTransfer = $this->currencyRepository->findCurrencyByIsoCode($isoCode);

        if ($currencyTransfer === null) {
            throw new CurrencyNotFoundException(
                sprintf('Currency with ISO code "%s" not found.', $isoCode),
            );
        }

        static::$currencyCache[$isoCode] = $currencyTransfer;

        return $currencyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getDefaultCurrencyForCurrentStore(): CurrencyTransfer
    {
        $defaultCurrencyIsoCode = $this->storeFacade->getCurrentStore()
            ->getDefaultCurrencyIsoCodeOrFail();

        return $this->getByIsoCode($defaultCurrencyIsoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected function getCurrenciesByIsoCodes(StoreTransfer $storeTransfer): array
    {
        $currencyTransfers = $this->currencyRepository->getCurrencyTransfersByIsoCodes($storeTransfer->getAvailableCurrencyIsoCodes());
        if ($currencyTransfers === []) {
            throw new CurrencyNotFoundException(
                sprintf(
                    "There is no currency configured for current store,
                    make sure you have currency ISO codes provided in 'currencyIsoCodes' array in current stores.php config.",
                ),
            );
        }

        return $currencyTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    protected function mapStoreCurrency(StoreTransfer $storeTransfer): StoreWithCurrencyTransfer
    {
        $storeWithCurrencyTransfer = new StoreWithCurrencyTransfer();
        $storeWithCurrencyTransfer->setStore($storeTransfer);
        $storeWithCurrencyTransfer->setCurrencies(
            new ArrayObject($this->getCurrenciesByIsoCodes($storeTransfer)),
        );

        return $storeWithCurrencyTransfer;
    }
}
