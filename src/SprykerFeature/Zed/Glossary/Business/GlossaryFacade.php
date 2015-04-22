<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Shared\Glossary\Transfer\Translation;
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

    public function synchronizeKeys()
    {
        $keyManager = $this->getDependencyContainer()->createKeyManager();

        $keyManager->synchronizeKeys();
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createTranslation($keyName, $localeName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createTranslation($keyName, $localeName, $value, $isActive);
    }

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
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createTranslationForCurrentLocale($keyName, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function createAndTouchTranslation($keyName, $localeName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->createAndTouchTranslation($keyName, $localeName, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return bool
     */
    public function hasTranslation($keyName, $localeName)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->hasTranslation($keyName, $localeName);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return Translation
     * @throws MissingTranslationException
     */
    public function getTranslation($keyName, $localeName)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();
        $translationEntity = $translationManager->getTranslationByNames($keyName, $localeName);

        return $translationManager->convertEntityToTranslationTransfer($translationEntity);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingTranslationException
     */
    public function updateTranslation($keyName, $localeName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->updateTranslation($keyName, $localeName, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param string $value
     * @param bool $isActive
     *
     * @return Translation
     * @throws MissingTranslationException
     */
    public function updateAndTouchTranslation($keyName, $localeName, $value, $isActive = true)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->updateAndTouchTranslation($keyName, $localeName, $value, $isActive);
    }

    /**
     * @param Translation $transferTranslation
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function saveTranslation(Translation $transferTranslation)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->saveTranslation($transferTranslation);
    }

    /**
     * @param Translation $transferTranslation
     *
     * @return Translation
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     */
    public function saveAndTouchTranslation(Translation $transferTranslation)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->saveAndTouchTranslation($transferTranslation);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return bool
     */
    public function deleteTranslation($keyName, $localeName)
    {
        $translationManager = $this->getDependencyContainer()->createTranslationManager();

        return $translationManager->deleteTranslation($keyName, $localeName);
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
