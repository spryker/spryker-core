<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;
use SprykerFeature\Zed\Glossary\Business\Exception\TranslationExistsException;

interface TranslationManagerInterface
{

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingTranslationException
     * @throws \Exception
     * @throws PropelException
     *
     * @return TranslationTransfer
     */
    public function updateTranslation($keyName, LocaleTransfer $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $transferTranslation);

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $locale);

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
     * @param LocaleTransfer $locale
     *
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function getTranslationByKeyName($keyName, LocaleTransfer $locale);

    /**
     * @param int $idKey
     */
    public function touchCurrentTranslationForKeyId($idKey);

}
