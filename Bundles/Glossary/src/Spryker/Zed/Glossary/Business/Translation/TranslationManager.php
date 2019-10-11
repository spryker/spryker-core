<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business\Translation;

use Exception;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;
use Propel\Runtime\Propel;
use Spryker\Zed\Glossary\Business\Exception\MissingKeyException;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\Glossary\Business\Exception\TranslationExistsException;
use Spryker\Zed\Glossary\Business\Key\KeyManagerInterface;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerInterface;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

class TranslationManager implements TranslationManagerInterface
{
    public const TOUCH_TRANSLATION = 'translation';
    public const GLOSSARY_KEY = 'glossary_key';
    public const LOCALE_PREFIX = 'locale_';

    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @var \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Glossary\Business\Key\KeyManagerInterface
     */
    protected $keyManager;

    /**
     * @var \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface $glossaryQueryContainer
     * @param \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface $touchFacade
     * @param \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Glossary\Business\Key\KeyManagerInterface $keyManager
     * @param \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerInterface $messengerFacade
     */
    public function __construct(
        GlossaryQueryContainerInterface $glossaryQueryContainer,
        GlossaryToTouchInterface $touchFacade,
        GlossaryToLocaleInterface $localeFacade,
        KeyManagerInterface $keyManager,
        GlossaryToMessengerInterface $messengerFacade
    ) {
        $this->glossaryQueryContainer = $glossaryQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->keyManager = $keyManager;
        $this->localeFacade = $localeFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        if (!$keyTranslationTransfer->getGlossaryKey()) {
            throw new MissingKeyException('Glossary Key cannot be empty');
        }

        $translationKey = $keyTranslationTransfer->getGlossaryKey();
        try {
            if (!$this->keyManager->hasKey($translationKey)) {
                $idGlossaryKey = $this->keyManager->createKey($translationKey);
            } else {
                $idGlossaryKey = $this->keyManager->getKey($translationKey)
                    ->getIdGlossaryKey();
            }

            $availableLocales = $this->localeFacade->getAvailableLocales();

            foreach ($availableLocales as $localeName) {
                $localeTransfer = $this->localeFacade->getLocale($localeName);

                if (array_key_exists($localeName, $keyTranslationTransfer->getLocales())) {
                    $translationValue = (string)$keyTranslationTransfer->getLocales()[$localeName];
                    $translationTransfer = $this->createTranslationTransfer($localeTransfer, $idGlossaryKey, $translationValue);
                    $this->saveAndTouchTranslation($translationTransfer);
                    if (!$translationValue) {
                        $this->deleteTranslation($translationKey, $localeTransfer);
                    }
                }
            }

            return true;
        } catch (MissingKeyException $error) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($error->getMessage());

            $this->messengerFacade->addErrorMessage($messageTransfer);

            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idGlossaryKey
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function createTranslationTransfer(LocaleTransfer $locale, $idGlossaryKey, $value, $isActive = true)
    {
        $translationTransfer = new TranslationTransfer();
        $translationTransfer->setValue($value);
        $translationTransfer->setFkGlossaryKey($idGlossaryKey);
        $translationTransfer->setFkLocale($locale->getIdLocale());
        $translationTransfer->setIsActive($isActive);

        return $translationTransfer;
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @return bool
     */
    protected function hasTranslationByIds($idKey, $idLocale)
    {
        $translationCount = $this->glossaryQueryContainer->queryTranslationByIds($idKey, $idLocale)
            ->count();

        return $translationCount > 0;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive)
    {
        $idKey = $this->keyManager->getKey($keyName)
            ->getPrimaryKey();
        $idLocale = $locale->getIdLocale();

        if ($idLocale === null) {
            $idLocale = $this->localeFacade->getLocale($locale->getLocaleName())
                ->getIdLocale();
        }

        return $this->createTranslationByIds($idKey, $idLocale, $value, $isActive);
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\TranslationExistsException
     *
     * @return void
     */
    protected function checkTranslationDoesNotExist($idKey, $idLocale)
    {
        if ($this->hasTranslationByIds($idKey, $idLocale)) {
            throw new TranslationExistsException(sprintf('Tried to create a translation for keyId %s, localeId %s, but it already exists', $idKey, $idLocale));
        }
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer === null) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
        }

        $translationCount = $this->glossaryQueryContainer->queryTranslationByNames($keyName, $localeTransfer->getLocaleName())
            ->count();

        return $translationCount > 0;
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function createTranslationByIds($idKey, $idLocale, $value, $isActive)
    {
        $this->checkTranslationDoesNotExist($idKey, $idLocale);

        $translation = new SpyGlossaryTranslation();

        $translation->setFkGlossaryKey($idKey)
            ->setFkLocale($idLocale)
            ->setValue($value)
            ->setIsActive($isActive);

        $translation->save();

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idItem
     *
     * @return void
     */
    protected function insertActiveTouchRecord($idItem)
    {
        $this->touchFacade->touchActive(self::TOUCH_TRANSLATION, $idItem);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $translation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function convertEntityToTranslationTransfer(SpyGlossaryTranslation $translation)
    {
        $transferTranslation = new TranslationTransfer();
        $transferTranslation->fromArray($translation->toArray());

        return $transferTranslation;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, LocaleTransfer $locale, $value, $isActive)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $locale, $value, $isActive);

        return $this->doUpdateTranslation($translation);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getUpdatedTranslationEntity($keyName, $locale, $value, $isActive)
    {
        $translation = $this->getTranslationEntityByNames($keyName, $locale->getLocaleName());

        $translation->setValue($value);
        $translation->setIsActive($isActive);

        return $translation;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslationByKeyName($keyName, LocaleTransfer $locale)
    {
        $translation = $this->getTranslationEntityByNames($keyName, $locale->getLocaleName());

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param string[] $keyNames
     * @param string[] $localeNames
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByKeyNames(array $keyNames, array $localeNames)
    {
        $translationTransfers = [];
        $fetchedKeys = [];
        $translations = $this->glossaryQueryContainer->queryTranslations()
            ->useGlossaryKeyQuery()
                ->filterByKey_In($keyNames)
                ->withColumn('key', 'glossaryKey')
            ->endUse()
            ->useLocaleQuery()
                ->filterByLocaleName_In($localeNames)
                ->withColumn('locale_name', 'localeName')
            ->endUse()
            ->find();

        foreach ($translations as $translation) {
            $translationTransfer = $this->convertEntityToTranslationTransfer($translation);
            $translationTransfers[] = $translationTransfer;
            $fetchedKeys[$translationTransfer->getLocaleName()][$translationTransfer->getGlossaryKey()] = true;
        }

        foreach ($localeNames as $localeName) {
            foreach ($keyNames as $keyName) {
                if (!isset($fetchedKeys[$localeName][$keyName])) {
                    throw new MissingTranslationException(sprintf('Could not find a translation for key %s, locale %s', $keyName, $localeName));
                }
            }
        }

        return $translationTransfers;
    }

    /**
     * @param int $idItem
     *
     * @return void
     */
    protected function insertDeletedTouchRecord($idItem)
    {
        $this->touchFacade->touchDeleted(self::TOUCH_TRANSLATION, $idItem);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $locale)
    {
        if (!$this->hasTranslation($keyName, $locale)) {
            return true;
        }

        $translation = $this->getTranslationEntityByNames($keyName, $locale->getLocaleName());

        $translation->setIsActive(false);

        if ($translation->isModified()) {
            $translation->save();
            $this->insertDeletedTouchRecord($translation->getPrimaryKey());
        }

        return true;
    }

    /**
     * @param array $idKeys
     *
     * @return void
     */
    public function deleteTranslationsByFkKeys(array $idKeys)
    {
        $translations = $this->glossaryQueryContainer
            ->queryGlossaryTranslationByFkGlossaryKeys($idKeys)
            ->find();

        foreach ($translations as $translation) {
            $this->insertDeletedTouchRecord($translation->getPrimaryKey());
        }

        $translations->delete();
    }

    /**
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer === null) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
        }

        $translation = $this->getTranslationByKeyName($keyName, $localeTransfer);

        return str_replace(array_keys($data), array_values($data), $translation->getValue());
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer $translationTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $translationTransfer)
    {
        if ($this->hasTranslationByIds($translationTransfer->getFkGlossaryKey(), $translationTransfer->getFkLocale())) {
            $translationEntity = $this->getTranslationByIds($translationTransfer->getFkGlossaryKey(), $translationTransfer->getFkLocale());
            $translationEntity->fromArray($translationTransfer->modifiedToArray());
            $translationEntity->save();

            $translationTransfer = new TranslationTransfer();
            $translationTransfer->fromArray($translationEntity->toArray(), true);
        } else {
            $translationTransfer = $this->createTranslationFromTransfer($translationTransfer);
        }

        return $translationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveAndTouchTranslation(TranslationTransfer $transferTranslation)
    {
        Propel::getConnection()->beginTransaction();

        try {
            $transferTranslation = $this->saveTranslation($transferTranslation);

            if ($transferTranslation->getIsActive()) {
                $this->insertActiveTouchRecord($transferTranslation->getIdGlossaryTranslation());
            }

            Propel::getConnection()->commit();
        } catch (Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $transferTranslation;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function createTranslationFromTransfer(TranslationTransfer $transferTranslation)
    {
        $newTransferTranslation = $this->createTranslationByIds($transferTranslation->getFkGlossaryKey(), $transferTranslation->getFkLocale(), $transferTranslation->getValue(), $transferTranslation->getIsActive());

        return $newTransferTranslation;
    }

    /**
     * @deprecated Not in use anymore. Will be removed with the next major.
     *
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function createAndTouchTranslationFromTransfer(TranslationTransfer $transferTranslation)
    {
        Propel::getConnection()
            ->beginTransaction();

        $transferTranslationNew = $this->createTranslationFromTransfer($transferTranslation);

        if ($transferTranslationNew->getIsActive()) {
            $this->insertActiveTouchRecord($transferTranslationNew->getIdGlossaryTranslation());
        }

        Propel::getConnection()
            ->commit();

        return $transferTranslationNew;
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getTranslationByIds($idKey, $idLocale)
    {
        $translation = $this->glossaryQueryContainer->queryTranslationByIds($idKey, $idLocale)
            ->findOne();

        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation for keyId %s, localeId %s', $idKey, $idLocale));
        }

        return $translation;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer $transferTranslation
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getTranslationFromTransfer(TranslationTransfer $transferTranslation)
    {
        $translation = $this->getTranslationEntityById($transferTranslation->getIdGlossaryTranslation());
        $translation->fromArray($transferTranslation->toArray());

        return $translation;
    }

    /**
     * @param int $idKey
     * @param array $data
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale();
        $translation = $this->getTranslationByIds($idKey, $idLocale);

        return str_replace(array_keys($data), array_values($data), $translation->getValue());
    }

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $idKey = $this->keyManager->getKey($keyName)
            ->getPrimaryKey();
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale();

        return $this->createTranslationByIds($idKey, $idLocale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        Propel::getConnection()
            ->beginTransaction();

        $translation = $this->createTranslation($keyName, $locale, $value, $isActive);
        if ($isActive) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        }
        Propel::getConnection()
            ->commit();

        return $translation;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $locale, $value, $isActive);

        return $this->doUpdateAndTouchTranslation($translation);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $translation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function doUpdateTranslation(SpyGlossaryTranslation $translation)
    {
        if ($translation->isModified()) {
            $translation->save();
        }

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $translation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function doUpdateAndTouchTranslation(SpyGlossaryTranslation $translation)
    {
        if (!$translation->isModified()) {
            return $this->convertEntityToTranslationTransfer($translation);
        }

        Propel::getConnection()
            ->beginTransaction();

        $isActiveModified = $translation->isColumnModified(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE);

        $translation->save();

        if ($translation->getIsActive()) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        } elseif ($isActiveModified) {
            $this->insertDeletedTouchRecord($translation->getIdGlossaryTranslation());
        }

        Propel::getConnection()
            ->commit();

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idTranslation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function getTranslationById($idTranslation)
    {
        $translation = $this->getTranslationEntityById($idTranslation);

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId($idKey)
    {
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale();
        $translation = $this->getTranslationByIds($idKey, $idLocale);
        $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
    }

    /**
     * @param int $idKey
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchTranslationForKeyId($idKey, ?LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer === null) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
        }

        $translation = $this->getTranslationByIds($idKey, $localeTransfer->getIdLocale());
        $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getTranslationEntityByNames($keyName, $localeName)
    {
        $translation = $this->glossaryQueryContainer->queryTranslationByNames($keyName, $localeName)
            ->findOne();
        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation for key %s, locale %s', $keyName, $localeName));
        }

        return $translation;
    }

    /**
     * @param int $idTranslation
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getTranslationEntityById($idTranslation)
    {
        $translation = $this->glossaryQueryContainer->queryTranslations()
            ->findPk($idTranslation);
        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation with id %s', $idTranslation));
        }

        return $translation;
    }
}
