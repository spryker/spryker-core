<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Business\Translation;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use Generated\Shared\Transfer\GlossaryTranslationTransfer;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;
use SprykerFeature\Zed\Glossary\Business\Exception\TranslationExistsException;

interface TranslationManagerInterface
{
    /**
     * @param string $keyName
     * @param LocaleDto $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createTranslation($keyName, LocaleDto $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createAndTouchTranslation($keyName, LocaleDto $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingTranslationException
     * @throws \Exception
     * @throws PropelException
     */
    public function updateTranslation($keyName, LocaleDto $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws MissingTranslationException
     */
    public function updateAndTouchTranslation($keyName, LocaleDto $locale, $value, $isActive = true);

    /**
     * @param Translation $transferTranslation
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     */
    public function saveTranslation(Translation $transferTranslation);

    /**
     * @param Translation $transferTranslation
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     */
    public function saveAndTouchTranslation(Translation $transferTranslation);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleDto $locale);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleDto $locale);

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate($keyName, array $data = []);

    /**
     * @param int $idKey
     * @param array $data
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = []);

    /**
     * @param string $keyName
     * @param LocaleDto $locale
     *
     * @return Translation
     * @throws MissingTranslationException
     */
    public function getTranslationByKeyName($keyName, LocaleDto $locale);

    /**
     * @param int $idKey
     */
    public function touchCurrentTranslationForKeyId($idKey);
}
