<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Business;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Glossary\Business\GlossaryBusinessFactory getFactory()
 */
class GlossaryFacade extends AbstractFacade
{

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->createKey($keyName);
    }

    /**
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
     * @param string $oldKeyName
     * @param string $newKeyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return bool
     */
    public function updateKey($oldKeyName, $newKeyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->updateKey($oldKeyName, $newKeyName);
    }

    /**
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
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createTranslation($keyName, $locale, $value, $isActive);
    }

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
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createTranslationForCurrentLocale($keyName, $value, $isActive);
    }

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
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->createAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale = null)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->getTranslationByKeyName($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->updateTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->updateAndTouchTranslation($keyName, $locale, $value, $isActive);
    }

    /**
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
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->saveTranslation($transferTranslation);
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation)
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->saveAndTouchTranslation($transferTranslation);
    }

    /**
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
     * @param string $keyName
     * @param array $data
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translate($keyName, array $data = [])
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->translate($keyName, $data);
    }

    /**
     * @param int $idKey
     * @param array $data
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $translationManager = $this->getFactory()->createTranslationManager();

        return $translationManager->translateByKeyId($idKey, $data);
    }

    /**
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
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName)
    {
        $keyManager = $this->getFactory()->createKeyManager();

        return $keyManager->getOrCreateKey($keyName);
    }

}
