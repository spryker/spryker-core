<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Glossary\Business\GlossaryBusinessFactory getFactory()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface getRepository()
 */
class GlossaryFacade extends AbstractFacade implements GlossaryFacadeInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @return int
     */
    public function createKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->createKey($keyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->hasKey($keyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->getKey($keyName)->getPrimaryKey();
    }

    /**
     * @api
     *
     * @param string $oldKeyName
     * @param string $newKeyName
     *
     * @return bool
     */
    public function updateKey($oldKeyName, $newKeyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->updateKey($oldKeyName, $newKeyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->deleteKey($keyName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idKeys
     *
     * @return void
     */
    public function deleteKeys(array $idKeys)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        $keyManager->deleteKeys($idKeys);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createTranslationForCurrentLocale($keyName, $value, $isActive);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $locale = null)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->hasTranslation($keyName, $locale);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->getTranslationByKeyName($keyName, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $keyNames
     * @param string[] $localeNames
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslations(array $keyNames, array $localeNames): array
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->getTranslationsByKeyNames($keyNames, $localeNames);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->updateTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->updateAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->saveTranslation($transferTranslation);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->saveAndTouchTranslation($transferTranslation);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $locale)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->deleteTranslation($keyName, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idKeys
     *
     * @return void
     */
    public function deleteTranslationsByFkKeys(array $idKeys)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        $translationManager->deleteTranslationsByFkKeys($idKeys);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->translate($keyName, $data, $localeTransfer);
    }

    /**
     * @api
     *
     * @param int $idKey
     * @param array $data
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->translateByKeyId($idKey, $data);
    }

    /**
     * @api
     *
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId($idKey)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        $translationManager->touchCurrentTranslationForKeyId($idKey);
    }

    /**
     * @api
     *
     * @param int $idKey
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchTranslationForKeyId($idKey, ?LocaleTransfer $localeTransfer = null)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        $translationManager->touchTranslationForKeyId($idKey, $localeTransfer);
    }

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->getOrCreateKey($keyName);
    }

    /**
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * @api
     *
     * @param string $keyFragment
     *
     * @return array
     */
    public function getKeySuggestions($keyFragment)
    {
        return $this->getFactory()
            ->createKeyManager()
            ->getKeySuggestions($keyFragment);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $glossaryKey
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByGlossaryKeyAndLocales(string $glossaryKey, array $localeTransfers): array
    {
        return $this->getFactory()
            ->createTranslationReader()
            ->getTranslationsByGlossaryKeyAndLocaleTransfers($glossaryKey, $localeTransfers);
    }
}
