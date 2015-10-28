<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
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
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingTranslationException
     * @throws \Exception
     * @throws PropelException
     */
    public function updateTranslation($keyName, LocaleTransfer $locale, $value, $isActive);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws MissingTranslationException
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param TranslationTransfer $translationTransfer
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     */
    public function saveTranslation(TranslationTransfer $translationTransfer);

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation);

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $localeTransfer = null);

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
     * @param LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], LocaleTransfer $localeTransfer = null);

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
     * @return TranslationTransfer
     * @throws MissingTranslationException
     */
    public function getTranslationByKeyName($keyName, LocaleTransfer $locale);

    /**
     * @param int $idKey
     */
    public function touchCurrentTranslationForKeyId($idKey);

}
