<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business;

use Generated\Shared\Transfer\CurrencyCollectionTransfer;
use Generated\Shared\Transfer\CurrencyCriteriaTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyBusinessFactory getFactory()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface getEntityManager()
 */
class CurrencyFacade extends AbstractFacade implements CurrencyFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode(string $isoCode): CurrencyTransfer
    {
        return $this->getFactory()->createCurrencyReader()->getByIsoCode($isoCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent(): CurrencyTransfer
    {
        return $this->getFactory()->createCurrencyBuilder()->getCurrent();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    public function createCurrency(CurrencyTransfer $currencyTransfer): int
    {
        return $this->getEntityManager()
            ->createCurrency($currencyTransfer)
            ->getIdCurrencyOrFail();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreCurrencies(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()->createCurrencyStoreWriter()
            ->updateStoreCurrencies($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validateStoreCurrencies(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()->createStoreCurrencyValidator()
            ->validateStoreCurrencies($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCurrency
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIdCurrency(int $idCurrency): CurrencyTransfer
    {
        return $this->getFactory()->createCurrencyReader()->getByIdCurrency($idCurrency);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getCurrentStoreWithCurrencies(): StoreWithCurrencyTransfer
    {
        return $this->getFactory()->createCurrencyReader()->getCurrentStoreWithCurrencies();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer>
     */
    public function getAllStoresWithCurrencies(): array
    {
        return $this->getFactory()->createCurrencyReader()->getAllStoresWithCurrencies();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getDefaultCurrencyForCurrentStore(): CurrencyTransfer
    {
        return $this->getFactory()
            ->createCurrencyReader()
            ->getDefaultCurrencyForCurrentStore();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateCurrencyInQuote(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->getFactory()->createQuoteValidator()->validate($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyByIsoCode(string $isoCode): ?CurrencyTransfer
    {
        return $this->getRepository()
            ->findCurrencyByIsoCode($isoCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array
    {
        return $this->getFactory()
            ->createCurrencyBulkReader()
            ->getCurrencyTransfersByIsoCodes($isoCodes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getStoreWithCurrenciesByIdStore(int $idStore): StoreWithCurrencyTransfer
    {
        return $this->getFactory()
            ->createCurrencyReader()
            ->getStoreWithCurrenciesByIdStore($idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCurrencies(array $storeTransfers): array
    {
        return $this->getFactory()
            ->createStoreExpander()
            ->expandStoreTransfersWithCurrencies($storeTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyCriteriaTransfer $currencyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollection(CurrencyCriteriaTransfer $currencyCriteriaTransfer): CurrencyCollectionTransfer
    {
        return $this->getRepository()
            ->getCurrencyCollection($currencyCriteriaTransfer);
    }
}
