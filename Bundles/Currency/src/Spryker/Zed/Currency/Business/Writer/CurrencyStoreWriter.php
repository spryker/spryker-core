<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface;
use Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CurrencyStoreWriter implements CurrencyStoreWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CURRENCY_NOT_EXISTS = 'The currency currency_code does not exist.';

    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface
     */
    protected CurrencyRepositoryInterface $currencyRepository;

    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface
     */
    protected CurrencyEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected CurrencyToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface $currencyRepository
     * @param \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        CurrencyEntityManagerInterface $entityManager,
        CurrencyToStoreFacadeInterface $storeFacade
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->entityManager = $entityManager;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreCurrencies(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->getSuccessfulResponse($storeTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($storeTransfer) {
            return $this->executeUpdateStoreCurrenciesTransaction($storeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function executeUpdateStoreCurrenciesTransaction(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = new StoreResponseTransfer();
        $currencyTransfers = $this->currencyRepository
            ->getCurrencyTransfersByIsoCodes($storeTransfer->getAvailableCurrencyIsoCodes());

        $this->entityManager->updateCurrencyStores(
            $storeTransfer,
            $currencyTransfers,
        );

        $defaultCurrencyTransfer = $this->currencyRepository->findCurrencyByIsoCode($storeTransfer->getDefaultCurrencyIsoCodeOrFail());
        if (!$defaultCurrencyTransfer) {
            return $storeResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::ERROR_MESSAGE_CURRENCY_NOT_EXISTS)
                        ->setParameters([
                            'currency_code' => $storeTransfer->getDefaultCurrencyIsoCodeOrFail(),
                        ]),
                );
        }

        $this->entityManager->updateStoreDefaultCurrency($defaultCurrencyTransfer, $storeTransfer);

        return $this->getSuccessfulResponse($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function getSuccessfulResponse(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);
    }
}
