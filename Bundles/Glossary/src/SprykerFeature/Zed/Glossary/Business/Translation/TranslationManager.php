<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Translation;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;
use SprykerFeature\Zed\Glossary\Business\Exception\TranslationExistsException;
use SprykerFeature\Zed\Glossary\Business\Key\KeyManagerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;

class TranslationManager implements TranslationManagerInterface
{

    const TOUCH_TRANSLATION = 'translation';
    const GLOSSARY_KEY = 'glossary_key';
    const LOCALE_PREFIX = 'locale_';

    /**
     * @var GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @var GlossaryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var KeyManagerInterface
     */
    protected $keyManager;

    /**
     * @var GlossaryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var FlashMessagesFacade
     */
    protected $flashMessagesFacade;

    /**
     * @param GlossaryQueryContainerInterface $glossaryQueryContainer
     * @param GlossaryToTouchInterface $touchFacade
     * @param GlossaryToLocaleInterface $localeFacade
     * @param KeyManagerInterface $keyManager
     */
    public function __construct(
        GlossaryQueryContainerInterface $glossaryQueryContainer,
        GlossaryToTouchInterface $touchFacade,
        GlossaryToLocaleInterface $localeFacade,
        KeyManagerInterface $keyManager,
        FlashMessengerFacade $flashMessengerFacade
    ) {
        $this->glossaryQueryContainer = $glossaryQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->keyManager = $keyManager;
        $this->localeFacade = $localeFacade;
        $this->flashMessagesFacade = $flashMessengerFacade;
    }

    /**
     * @param KeyTranslationTransfer $keyTranslationTransfer
     *
     * @throws MissingKeyException
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        if (empty($keyTranslationTransfer->getGlossaryKey())) {
            throw new MissingKeyException('Glossary Key cannot be empty');
        }

        try {
            if (!$this->keyManager->hasKey($keyTranslationTransfer->getGlossaryKey())) {
                $idGlossaryKey = $this->keyManager->createKey($keyTranslationTransfer->getGlossaryKey());
            } else {
                $idGlossaryKey = $this->keyManager->getKey($keyTranslationTransfer->getGlossaryKey())
                    ->getIdGlossaryKey()
                ;
            }

            $availableLocales = $this->localeFacade->getAvailableLocales();

            foreach ($availableLocales as $localeName) {
                $localeTransfer = $this->localeFacade->getLocale($localeName);

                if (isset($keyTranslationTransfer->getLocales()[$localeName])) {
                    $translationTransfer = $this->createTranslationTransfer($localeTransfer, $idGlossaryKey, $keyTranslationTransfer->getLocales()[$localeName]);
                    $this->saveAndTouchTranslation($translationTransfer);
                }
            }

            return true;
        } catch (MissingKeyException $error) {
            $this->flashMessages->addErrorMessage($error->getMessage());

            return false;
        }
    }

    /**
     * @param LocaleTransfer $locale
     * @param string $idGlossaryKey
     * @param string $value
     * @param bool $isActive
     *
     * @return TranslationTransfer
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
            ->count()
        ;

        return $translationCount > 0;
    }

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
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive)
    {
        $idKey = $this->keyManager->getKey($keyName)
            ->getPrimaryKey()
        ;
        $idLocale = $locale->getIdLocale();

        if ($idLocale === null) {
            $idLocale = $this->localeFacade->getLocale($locale->getLocaleName())
                ->getIdLocale()
            ;
        }

        return $this->createTranslationByIds($idKey, $idLocale, $value, $isActive);
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @throws TranslationExistsException
     */
    protected function checkTranslationDoesNotExist($idKey, $idLocale)
    {
        if ($this->hasTranslationByIds($idKey, $idLocale)) {
            throw new TranslationExistsException(sprintf('Tried to create a translation for keyId %s, localeId %s, but it already exists', $idKey, $idLocale));
        };
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer === null) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
        }

        $translationCount = $this->glossaryQueryContainer->queryTranslationByNames($keyName, $localeTransfer->getLocaleName())
            ->count()
        ;

        return $translationCount > 0;
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return TranslationTransfer
     */
    protected function createTranslationByIds($idKey, $idLocale, $value, $isActive)
    {
        $this->checkTranslationDoesNotExist($idKey, $idLocale);

        $translation = new SpyGlossaryTranslation();

        $translation->setFkGlossaryKey($idKey)
            ->setFkLocale($idLocale)
            ->setValue($value)
            ->setIsActive($isActive)
        ;

        $translation->save();

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idItem
     */
    protected function insertActiveTouchRecord($idItem)
    {
        $this->touchFacade->touchActive(self::TOUCH_TRANSLATION, $idItem);
    }

    /**
     * @param SpyGlossaryTranslation $translation
     *
     * @return TranslationTransfer
     */
    protected function convertEntityToTranslationTransfer(SpyGlossaryTranslation $translation)
    {
        $transferTranslation = new TranslationTransfer();
        $transferTranslation->fromArray($translation->toArray());

        return $transferTranslation;
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingTranslationException
     * @throws PropelException
     *
     * @return TranslationTransfer
     */
    public function updateTranslation($keyName, LocaleTransfer $locale, $value, $isActive)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $locale, $value, $isActive);

        return $this->doUpdateTranslation($translation);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingTranslationException
     *
     * @return SpyGlossaryTranslation
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
     * @param LocaleTransfer $locale
     *
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function getTranslationByKeyName($keyName, LocaleTransfer $locale)
    {
        $translation = $this->getTranslationEntityByNames($keyName, $locale->getLocaleName());

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idItem
     */
    protected function insertDeletedTouchRecord($idItem)
    {
        $this->touchFacade->touchDeleted(self::TOUCH_TRANSLATION, $idItem);
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
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
     * @param string $keyName
     * @param array $data
     * @param LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer === null) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
        }

        $translation = $this->getTranslationByKeyName($keyName, $localeTransfer);

        return str_replace(array_keys($data), array_values($data), $translation->getValue());
    }

    /**
     * @param TranslationTransfer $translationTransfer
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function saveTranslation(TranslationTransfer $translationTransfer)
    {
        if ($this->hasTranslationByIds($translationTransfer->getFkGlossaryKey(), $translationTransfer->getFkLocale())) {
            $translationEntity = $this->getTranslationByIds($translationTransfer->getFkGlossaryKey(), $translationTransfer->getFkLocale());
            $translationEntity->setValue($translationTransfer->getValue());
            $translationEntity->save();

            $translationTransfer = new TranslationTransfer();
            $translationTransfer->fromArray($translationEntity->toArray(), true);
        } else {
            $translationTransfer = $this->createTranslationFromTransfer($translationTransfer);
        }

        return $translationTransfer;
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     * @throws MissingTranslationException
     * @throws \Exception
     *
     * @return TranslationTransfer
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
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $transferTranslation;
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @return TranslationTransfer
     */
    protected function createTranslationFromTransfer(TranslationTransfer $transferTranslation)
    {
        $newTransferTranslation = $this->createTranslationByIds($transferTranslation->getFkGlossaryKey(), $transferTranslation->getFkLocale(), $transferTranslation->getValue(), $transferTranslation->getIsActive());

        return $newTransferTranslation;
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @return SpyGlossaryTranslation
     */
    protected function createAndTouchTranslationFromTransfer(TranslationTransfer $transferTranslation)
    {
        Propel::getConnection()
            ->beginTransaction()
        ;

        $transferTranslationNew = $this->createTranslationFromTransfer($transferTranslation);

        if ($transferTranslationNew->getIsActive()) {
            $this->insertActiveTouchRecord($transferTranslationNew->getIdGlossaryTranslation());
        }

        Propel::getConnection()
            ->commit()
        ;

        return $transferTranslationNew;
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @throws MissingTranslationException
     *
     * @return SpyGlossaryTranslation
     */
    protected function getTranslationByIds($idKey, $idLocale)
    {
        $translation = $this->glossaryQueryContainer->queryTranslationByIds($idKey, $idLocale)
            ->findOne()
        ;

        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation for keyId %s, localeId %s', $idKey, $idLocale));
        }

        return $translation;
    }

    /**
     * @param TranslationTransfer $transferTranslation
     *
     * @throws MissingTranslationException
     *
     * @return SpyGlossaryTranslation
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
     * @throws MissingTranslationException
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = [])
    {
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale()
        ;
        $translation = $this->getTranslationByIds($idKey, $idLocale);

        return str_replace(array_keys($data), array_values($data), $translation->getValue());
    }

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return SpyGlossaryTranslation
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true)
    {
        $idKey = $this->keyManager->getKey($keyName)
            ->getPrimaryKey()
        ;
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale()
        ;

        return $this->createTranslationByIds($idKey, $idLocale, $value, $isActive);
    }

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
     * @return SpyGlossaryTranslation
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        Propel::getConnection()
            ->beginTransaction()
        ;

        $translation = $this->createTranslation($keyName, $locale, $value, $isActive);
        if ($isActive) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        }
        Propel::getConnection()
            ->commit()
        ;

        return $translation;
    }

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
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        $translation = $this->getUpdatedTranslationEntity($keyName, $locale, $value, $isActive);

        return $this->doUpdateAndTouchTranslation($translation);
    }

    /**
     * @param SpyGlossaryTranslation $translation
     *
     * @return TranslationTransfer
     */
    protected function doUpdateTranslation(SpyGlossaryTranslation $translation)
    {
        if ($translation->isModified()) {
            $translation->save();
        }

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param SpyGlossaryTranslation $translation
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return TranslationTransfer
     */
    protected function doUpdateAndTouchTranslation(SpyGlossaryTranslation $translation)
    {
        if (!$translation->isModified()) {
            return $translation;
        }

        Propel::getConnection()
            ->beginTransaction()
        ;

        $isActiveModified = $translation->isColumnModified(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE);

        $translation->save();

        if ($translation->getIsActive()) {
            $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
        } elseif ($isActiveModified) {
            $this->insertDeletedTouchRecord($translation->getIdGlossaryTranslation());
        }

        Propel::getConnection()
            ->commit()
        ;

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idTranslation
     *
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    protected function getTranslationById($idTranslation)
    {
        $translation = $this->getTranslationEntityById($idTranslation);

        return $this->convertEntityToTranslationTransfer($translation);
    }

    /**
     * @param int $idKey
     */
    public function touchCurrentTranslationForKeyId($idKey)
    {
        $idLocale = $this->localeFacade->getCurrentLocale()
            ->getIdLocale()
        ;
        $translation = $this->getTranslationByIds($idKey, $idLocale);
        $this->insertActiveTouchRecord($translation->getIdGlossaryTranslation());
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @throws MissingTranslationException
     *
     * @return SpyGlossaryTranslation
     */
    protected function getTranslationEntityByNames($keyName, $localeName)
    {
        $translation = $this->glossaryQueryContainer->queryTranslationByNames($keyName, $localeName)
            ->findOne()
        ;
        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation for key %s, locale %s', $keyName, $localeName));
        }

        return $translation;
    }

    /**
     * @param int $idTranslation
     *
     * @throws MissingTranslationException
     *
     * @return SpyGlossaryTranslation
     */
    protected function getTranslationEntityById($idTranslation)
    {
        $translation = $this->glossaryQueryContainer->queryTranslations()
            ->findPk($idTranslation)
        ;
        if (!$translation) {
            throw new MissingTranslationException(sprintf('Could not find a translation with id %s', $idTranslation));
        }

        return $translation;
    }

}
