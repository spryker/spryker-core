<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface GlossaryFacadeInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier($keyName);

    /**
     * @api
     *
     * @param string $oldKeyName
     * @param string $newKeyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return bool
     */
    public function updateKey($oldKeyName, $newKeyName);

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName);

    /**
     * Specification:
     *  - Deletes keys from database with the given idKeys
     *
     * @api
     *
     * @param array $idKeys
     *
     * @return bool
     */
    public function deleteKeys(array $idKeys);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @api
     *
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $locale = null);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $transferTranslation);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation);

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $locale);

    /**
     * Specification:
     *  - Deletes translations from database with the given idKeys
     *
     * @api
     *
     * @param array $idKeys
     *
     * @return bool
     */
    public function deleteTranslationsByFkKeys(array $idKeys);

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null);

    /**
     * @api
     *
     * @param int $idKey
     * @param array $data
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = []);

    /**
     * @api
     *
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId($idKey);

    /**
     * @api
     *
     * @param int $idKey
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchTranslationForKeyId($idKey, ?LocaleTransfer $localeTransfer = null);

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName);

    /**
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * @api
     *
     * @param string $keyFragment
     *
     * @return array
     */
    public function getKeySuggestions($keyFragment);
}
