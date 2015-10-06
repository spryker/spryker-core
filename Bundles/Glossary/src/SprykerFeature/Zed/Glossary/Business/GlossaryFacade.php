<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Glossary\Business\Exception;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;
use SprykerFeature\Zed\Glossary\Business\Exception\TranslationExistsException;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossaryFacade extends AbstractFacade
{

    /**
     * @param string $keyName
     *
     * @return int
     * @throws KeyExistsException
     */
    public function createKey($keyName)
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        return $keyManager->createKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        return $keyManager->hasKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier($keyName)
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        return $keyManager->getKey($keyName)->getPrimaryKey();
    }

    /**
     * @param string $oldKeyName
     * @param string $newKeyName
     *
     * @return bool
     * @throws MissingKeyException
     */
    public function updateKey($oldKeyName, $newKeyName)
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        return $keyManager->updateKey($oldKeyName, $newKeyName);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName)
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        return $keyManager->deleteKey($keyName);
    }

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
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createTranslation($keyName, $locale, $value, $isActive);
    }

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
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createTranslationForCurrentLocale($keyName, $value, $isActive);
    }

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
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale = null)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     *
     * @return TranslationTransfer
     * @throws MissingTranslationException
     */
    public function getTranslation($keyName, LocaleTransfer $locale)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->getTranslationByKeyName($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingTranslationException
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->updateTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
     * @throws MissingTranslationException
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->updateAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param array $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function saveTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->saveTranslation($transferTranslation);
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @return TranslationTransfer
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->saveAndTouchTranslation($transferTranslation);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $locale)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->deleteTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     * @throws MissingTranslationException
     */
    public function translate($keyName, array $data = [])
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->translate($keyName, $data);
    }

    /**
     * @param int $idKey
     * @param array $data
     *
     * @return string
     * @throws MissingTranslationException
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->translateByKeyId($idKey, $data);
    }

    /**
     * @param int $idKey
     */
    public function touchCurrentTranslationForKeyId($idKey)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        $translationManager->touchCurrentTranslationForKeyId($idKey);
    }
}
