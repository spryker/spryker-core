<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Writer;

use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Reader\LocaleReaderInterface;
use Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface;

class LocaleWriter implements LocaleWriterInterface
{
    /**
     * @var \Spryker\Zed\Locale\Business\Reader\LocaleReaderInterface
     */
    protected LocaleReaderInterface $localeReader;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface
     */
    protected LocaleEntityManagerInterface $localeEntityManager;

    /**
     * @param \Spryker\Zed\Locale\Business\Reader\LocaleReaderInterface $localeReader
     * @param \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface $localeEntityManager
     */
    public function __construct(
        LocaleReaderInterface $localeReader,
        LocaleEntityManagerInterface $localeEntityManager
    ) {
        $this->localeReader = $localeReader;
        $this->localeEntityManager = $localeEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function createLocaleStore(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $localeTransfers = $this->localeReader->getLocaleCollection(
            (new LocaleCriteriaTransfer())
                ->setLocaleConditions((new LocaleConditionsTransfer())
                    ->setLocaleNames($storeTransfer->getAvailableLocaleIsoCodes())),
        );

        foreach ($localeTransfers as $localeTransfer) {
            $this->localeEntityManager
                ->createLocaleStore($storeTransfer, $localeTransfer);
        }

        $storeResponseTransfer = new StoreResponseTransfer();

        return $storeResponseTransfer->setIsSuccessful(true)
            ->setStore($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreLocales(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $localeTransfers = $this->localeReader->getLocaleCollection(
            (new LocaleCriteriaTransfer())
                ->setLocaleConditions((new LocaleConditionsTransfer())
                    ->setLocaleNames($storeTransfer->getAvailableLocaleIsoCodes())),
        );

        $this->localeEntityManager->updateStoreLocales($storeTransfer, $localeTransfers);

        return $this->getSuccessfulResponse($storeTransfer);
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale(string $localeName): LocaleTransfer
    {
        $this->assertLocaleDoesNotExist($localeName);

        return $this->localeEntityManager->createLocale($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale(string $localeName): void
    {
        $this->localeEntityManager->deleteLocale($localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreDefaultLocale(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $localeTransfer = $this->localeReader->getLocaleByName($storeTransfer->getDefaultLocaleIsoCodeOrFail());

        $this->localeEntityManager->updateStoreDefaultLocale(
            $storeTransfer,
            $localeTransfer,
        );

        return $this->getSuccessfulResponse($storeTransfer);
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     *
     * @return void
     */
    protected function assertLocaleDoesNotExist(string $localeName): void
    {
        if ($this->localeReader->localeExists($localeName)) {
            throw new LocaleExistsException(
                sprintf(
                    'Tried to create locale %s, but it already exists',
                    $localeName,
                ),
            );
        }
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
