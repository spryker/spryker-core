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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Symfony\Contracts\Translation\TranslatorTrait;

class TranslationManager implements TranslationManagerInterface
{
    use TransactionTrait;
    use TranslatorTrait;

    /**
     * @var string
     */
    public const TOUCH_TRANSLATION = 'translation';

    /**
     * @var string
     */
    public const GLOSSARY_KEY = 'glossary_key';

    /**
     * @var string
     */
    public const LOCALE_PREFIX = 'locale_';

    /**
     * @var string
     */
    protected const TRANSLATION_VALUE_ZERO = '0';

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

        /** @var string|null $translationKey */
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
                    if (!$this->isTranslationValueValid($translationValue)) {
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idGlossaryKey
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function createTranslationTransfer(LocaleTransfer $localeTransfer, $idGlossaryKey, $value, $isActive = true)
    {
        $translationTransfer = new TranslationTransfer();
        $translationTransfer->setValue($value);
        $translationTransfer->setFkGlossaryKey($idGlossaryKey);
        $translationTransfer->setFkLocale($localeTransfer->getIdLocale());
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $localeTransfer, $value, $isActive)
    {
        $idKey = $this->keyManager->getKey($keyName)
            ->getPrimaryKey();
        $idLocale = $localeTransfer->getIdLocale();

        if ($idLocale === null) {
            $idLocale = $this->localeFacade->getLocale($localeTransfer->getLocaleName())
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
        $this->touchFacade->touchActive(static::TOUCH_TRANSLATION, $idItem);
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, LocaleTransfer $localeTransfer, $value, $isActive)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $localeTransfer, $value, $isActive);

        return $this->doUpdateTranslation($translation);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation
     */
    protected function getUpdatedTranslationEntity($keyName, $localeTransfer, $value, $isActive)
    {
        $translation = $this->getTranslationEntityByNames($keyName, $localeTransfer->getLocaleName());

        $translation->setValue($value);
        $translation->setIsActive($isActive);

        return $translation;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslationByKeyName($keyName, LocaleTransfer $localeTransfer)
    {
        $translation = $this->getTranslationEntityByNames($keyName, $localeTransfer->getLocaleName());

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idItem
     *
     * @return void
     */
    protected function insertDeletedTouchRecord($idItem)
    {
        $this->touchFacade->touchDeleted(static::TOUCH_TRANSLATION, $idItem);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function deleteTranslation($keyName, LocaleTransfer $localeTransfer)
    {
        if (!$this->hasTranslation($keyName, $localeTransfer)) {
            return true;
        }

        $translation = $this->getTranslationEntityByNames($keyName, $localeTransfer->getLocaleName());

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
        /** @var \Propel\Runtime\Collection\ObjectCollection $translations */
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
     * @param array<string, mixed> $data
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

        return $this->trans($translation->getValue(), $data, null, $localeTransfer->getLocaleName());
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
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $translation = $this->getTranslationByIds($idKey, $localeTransfer->getIdLocale());

        return $this->trans($translation->getValue(), $data, null, $localeTransfer->getLocaleName());
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $localeTransfer, $value, $isActive = true)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($keyName, $localeTransfer, $value, $isActive): TranslationTransfer {
            return $this->executeCreateAndTouchTranslationTransaction($keyName, $localeTransfer, $value, $isActive);
        });
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $localeTransfer, $value, $isActive = true)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $localeTransfer, $value, $isActive);

        return $this->doUpdateAndTouchTranslation($translation);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function executeCreateAndTouchTranslationTransaction(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value,
        bool $isActive = true
    ): TranslationTransfer {
        $translation = $this->createTranslation($keyName, $localeTransfer, $value, $isActive);
        if ($isActive) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        }

        return $translation;
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

        return $this->getTransactionHandler()->handleTransaction(function () use ($translation): TranslationTransfer {
            return $this->executeUpdateAndTouchTranslationTransaction($translation);
        });
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $translation
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function executeUpdateAndTouchTranslationTransaction(SpyGlossaryTranslation $translation): TranslationTransfer
    {
        $isActiveModified = $translation->isColumnModified(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE);

        $translation->save();

        if ($translation->getIsActive()) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        } elseif ($isActiveModified) {
            $this->insertDeletedTouchRecord($translation->getIdGlossaryTranslation());
        }

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

        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation */
        return $translation;
    }

    /**
     * @param string $translationValue
     *
     * @return bool
     */
    protected function isTranslationValueValid(string $translationValue): bool
    {
        return $translationValue
            || $translationValue === static::TRANSLATION_VALUE_ZERO;
    }
}
