<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleBusinessFactory getFactory()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
 * @method \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface getEntityManager()
 */
class LocaleFacade extends AbstractFacade implements LocaleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale(string $localeName): bool
    {
        return $this->getFactory()
            ->createLocaleReader()
            ->localeExists($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale(string $localeName): LocaleTransfer
    {
        return $this->getFactory()
            ->createLocaleReader()
            ->getLocaleByName($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleById(int $idLocale): LocaleTransfer
    {
        return $this->getFactory()
            ->createLocaleReader()
            ->getLocaleById($idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocaleName(): string
    {
        return $this->getFactory()->getCurrentLocale();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAvailableLocales(): array
    {
        $localeReader = $this->getFactory()->createLocaleReader();

        return $localeReader->getAvailableLocales();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer
    {
        $localeName = $this->getCurrentLocaleName();

        return $this->getLocale($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale(string $localeName): LocaleTransfer
    {
        return $this->getFactory()
            ->createLocaleWriter()
            ->createLocale($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale(string $localeName): void
    {
        $this->getFactory()
            ->createLocaleWriter()
            ->deleteLocale($localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array
    {
        return $this->getFactory()
            ->createLocaleReader()
            ->getLocaleCollection($localeCriteriaTransfer);
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
    public function expandStoreTransfersWithLocales(array $storeTransfers): array
    {
        return $this->getFactory()
            ->createStoreExpander()
            ->expandStoreTransfersWithLocales($storeTransfers);
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
    public function updateStoreDefaultLocale(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()
            ->createLocaleWriter()
            ->updateStoreDefaultLocale($storeTransfer);
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
    public function validateStoreLocale(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()
            ->createLocaleValidator()
            ->validateStoreLocale($storeTransfer);
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
    public function updateStoreLocales(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()->createLocaleWriter()
            ->updateStoreLocales($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSupportedLocaleCodes(): array
    {
        return $this->getFactory()->getConfig()->getBackofficeUILocales();
    }
}
