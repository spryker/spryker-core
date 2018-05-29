<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CmsToGlossaryInterface
{
    /**
     * @param int $idKey
     * @param array $data
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = []);

    /**
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
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier($keyName);

    /**
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId($idKey);

    /**
     * @param int $idKey
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchTranslationForKeyId($idKey, ?LocaleTransfer $localeTransfer = null);

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName);

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer);

    /**
     * @param array $idKeys
     *
     * @return bool
     */
    public function deleteTranslationsByFkKeys(array $idKeys);

    /**
     * @param array $idKeys
     *
     * @return bool
     */
    public function deleteKeys(array $idKeys);
}
