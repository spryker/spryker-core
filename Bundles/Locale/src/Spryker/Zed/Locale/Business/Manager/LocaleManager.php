<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business\Manager;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Business\TransferGeneratorInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Orm\Zed\Locale\Persistence\SpyLocale;

class LocaleManager
{

    /**
     * @var LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var TransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function __construct(LocaleQueryContainerInterface $localeQueryContainer,
        TransferGeneratorInterface $transferGenerator,
        LocatorLocatorInterface $locator
    ) {
        $this->localeQueryContainer = $localeQueryContainer;
        $this->transferGenerator = $transferGenerator;
        $this->locator = $locator;
    }

    /**
     * @param string $localeName
     *
     * @throws MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName)
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);
        $locale = $localeQuery->findOne();
        if (!$locale) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale %s, but it does not exist',
                    $localeName
                )
            );
        }

        return $this->transferGenerator->convertLocale($locale);
    }

    /**
     * @param string $localeName
     *
     * @throws LocaleExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return LocaleTransfer
     */
    public function createLocale($localeName)
    {
        if ($this->hasLocale($localeName)) {
            throw new LocaleExistsException(
                sprintf(
                    'Tried to create locale %s, but it already exists',
                    $localeName
                )
            );
        }

        $locale = new SpyLocale();
        $locale->setLocaleName($localeName);

        $locale->save();

        return $this->transferGenerator->convertLocale($locale);
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);

        return $localeQuery->count() > 0;
    }

    /**
     * @param string $localeName
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function deleteLocale($localeName)
    {
        if (!$this->hasLocale($localeName)) {
            return true;
        }

        $locale = $this->localeQueryContainer
            ->queryLocaleByName($localeName)
            ->findOne();

        $locale->setIsActive(false);
        $locale->save();

        return true;
    }

}
